# LAPORAN ANALISA & TEMUAN: PENYEBAB DATA SISWA HILANG, LM TERTIMPA, DAN PENIMPAAN ROMBEL

**Tanggal Analisis:** 27 Oktober 2026  
**Aplikasi:** eRapor SMP IT Addurrah (CodeIgniter 4 Backend)  
**Dokumen Terkait:**  
- [panduan_capture_dan_restore_siswa_rombel_cli.md](file:///d:/laragon/htdocs/erapoteasy/docs/panduan_capture_dan_restore_siswa_rombel_cli.md)  
- [capture_siswa_rombel.php](file:///d:/laragon/htdocs/erapoteasy/capture_siswa_rombel.php)  
- [restore_siswa_rombel.php](file:///d:/laragon/htdocs/erapoteasy/restore_siswa_rombel.php)  

---

## DAFTAR ISI
1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Temuan 1: Analisis Hilangnya Siswa dari Database](#2-temuan-1-analisis-hilangnya-siswa-dari-database)
3. [Temuan 2: Analisis Hilang / Tertimpanya Lingkup Materi (LM)](#3-temuan-2-analisis-hilang--tertimpanya-lingkup-materi-lm)
4. [Temuan 3: Skrip Import yang Menimpa Nama Kelas & Tingkat Rombel](#4-temuan-3-skrip-import-yang-menimpa-nama-kelas--tingkat-rombel)
5. [Temuan 4: Penyebab Siswa Duplikat & Nilai Gagal Masuk di Template Kolektif](#5-temuan-4-penyebab-siswa-duplikat--nilai-gagal-masuk-di-template-kolektif)
6. [Temuan 5: Perbedaan Perhitungan Nilai Sistem vs DKN Manual Guru](#6-temuan-5-perbedaan-perhitungan-nilai-sistem-vs-dkn-manual-guru)
7. [Tindakan Perbaikan yang Telah Diimplementasikan](#7-tindakan-perbaikan-yang-telah-diimplementasikan)
8. [Panduan & Rekomendasi Mitigasi untuk Admin/Tim IT](#8-panduan--rekomendasi-mitigasi-untuk-admintim-it)

---

## 1. RINGKASAN EKSEKUTIF

Sepanjang investigasi teknis terhadap laporan berkurangnya data siswa (seperti 7 siswa kelas Kyanite), hilangnya indikator Lingkup Materi (LM), serta ketidaksesuaian nilai pada template kolektif, ditemukan **tiga akar masalah utama**:

1. **Bukan Modul Guru**: Tidak ada modul Guru Mapel, Wali Kelas, maupun Orang Tua yang memiliki hak akses atau kode untuk menghapus siswa dari database.
2. **Bug Regex Angka Romawi pada Import LM**: Parser Excel di `MasterLmController` gagal membaca format kelas Romawi (`VIII`, `IX`), sehingga otomatis mengalihkan semua LM tingkat 8 dan 9 menjadi tingkat 7 dan menimpa LM tingkat 7, meninggalkan kelas 8 dan 9 kosong tanpa LM.
3. **Mekanisme Overwrite pada Import Rombel & Siswa**:
   - Import Rombel meng-`update` data jika Kolom A (ID) terisi, sehingga rombel lama tertimpa nama kelas dan tingkatnya.
   - Import Siswa mengambil rombel tanpa memfilter Tahun Ajaran Aktif, sehingga nama kelas yang sama lintas tahun ajaran menyebabkan siswa terpetakan ke ID rombel lama.

---

## 2. TEMUAN 1: ANALISIS HILANGNYA SISWA DARI DATABASE

### A. Audit Seluruh Query Penghapusan (`delete()`)
Pemeriksaan menyeluruh terhadap seluruh codebase menemukan **51 pemanggilan fungsi `delete()`**. Dari ke-51 titik tersebut:
- **Hanya ada 1 tempat** di seluruh aplikasi yang menghapus record master dari tabel `siswa`:
  - **File:** `backend/app/Controllers/Admin/SiswaController.php` (baris 708 pada fungsi `delete($id)`).
- Modul Nilai (Formatif, Sumatif, Rapor), Modul Kehadiran, dan Modul Orang Tua **sama sekali tidak memiliki script penghapusan siswa**.

### B. Celah pada Fungsi Hapus Siswa Admin
Sebelum diperbaiki, fungsi `delete($id)` pada `SiswaController`:
1. **Tidak melakukan validasi riwayat nilai**: Jika tombol hapus diklik di panel Admin Siswa, siswa langsung terhapus meskipun sudah memiliki nilai formatif, sumatif, dan nilai rapor.
2. **Foreign Key Cascade**: Karena relasi tabel `anggota_rombel`, `nilai_formatif`, `nilai_sumatif`, dan `nilai_rapor` menggunakan relasi `siswa_id`, penghapusan master siswa langsung memusnahkan seluruh jejak akademik siswa tersebut.

### C. Pemetaan Rombel Tanpa Filter Tahun Ajaran pada Import Siswa
Pada `SiswaController::import()`:
```php
// SEBELUM PERBAIKAN:
$dbRombelQuery = $rombelModel->findAll(); // Mengambil SEMUA rombel lintas tahun ajaran!
```
- Nama rombel berulang setiap tahun ajaran (contoh: *Kyanite* ada di TA 2023/2024 dan 2024/2025).
- Jika `$mapRombel` membaca rombel tahun sebelumnya terlebih dahulu atau tertimpa oleh ID lama, siswa baru dimasukkan ke `rombel_id` tahun ajaran yang salah, sehingga siswa tidak muncul di rombel tahun ajaran aktif.

---

## 3. TEMUAN 2: ANALISIS HILANG / TERTIMPANYA LINGKUP MATERI (LM)

### A. Arsitektur Data `master_lm`
Tabel `master_lm` **tidak menyimpan `rombel_id`** ataupun `nama_rombel`. Struktur tabelnya adalah:
- `id`
- `mapel_id`
- `tingkat` (angka: `7`, `8`, `9`)
- `semester` (`Ganjil` / `Genap`)
- `kategori` (`Tengah` / `Akhir`)
- `kode_lm` (contoh: `LM 1`, `LM 2`)
- `deskripsi_lm`

Saat Guru Mapel atau Wali Kelas membuka form penilaian atau cetak rapor, sistem memanggil LM dengan query:
```php
$tingkat = $rombel['tingkat'];
$lmList = $masterLmModel->where('tingkat', $tingkat)
                        ->where('mapel_id', $mapelId)
                        ->where('semester', $semester)
                        ->findAll();
```

### B. Akar Masalah: Bug Regex Angka Romawi pada Import LM
Pada `backend/app/Controllers/Admin/MasterLmController.php:705-706`:
```php
// SEBELUM PERBAIKAN:
preg_match('/(\d+)/', $tingkat_str, $matches);
$tingkat = isset($matches[1]) ? (int)$matches[1] : 7;
```

**Kronologi Kejadian:**
1. Template Excel LM sering kali diisi kolom Kelas/Tingkat dengan angka Romawi: **`VII`**, **`VIII`**, **`IX`**, atau nama kelas seperti **`Kyanite`**.
2. Pola regex `(\d+)` hanya mendeteksi angka numerik (`0-9`). Pada teks `"VIII"` atau `"IX"`, regex **tidak menemukan digit apa pun**.
3. Kode otomatis mengeksekusi fallback bawaan: **`$tingkat = 7`**.
4. Akibatnya:
   - Data LM untuk Tingkat 8 dan 9 disimpan ke database dengan kolom `tingkat = 7`.
   - Karena import mengecek duplikasi berdasarkan kombinasi `(mapel_id, tingkat, semester, kategori, kode_lm)`, data LM Kelas 7 yang asli **tertimpa (overwritten)** oleh data LM kelas 8/9.
   - Sementara itu, di database tidak ada baris LM yang berstatus `tingkat = 8` atau `tingkat = 9`.
   - Ketika guru kelas 8 dan 9 membuka form nilai, query menghasilkan **0 data** (LM tampak seolah-olah "terhapus").

---

## 4. TEMUAN 3: SKRIP IMPORT YANG MENIMPA NAMA KELAS & TINGKAT ROMBEL

### A. Overwrite Berdasarkan ID pada Import Rombel
Pada `backend/app/Controllers/Admin/TingkatRombelController.php:583`:
```php
$idRombel = !empty($row[0]) ? (int)$row[0] : null; // Kolom A = ID
...
if ($idRombel && $rombelModel->find($idRombel)) {
    $rombelModel->update($idRombel, $data); // <--- MENIMPA NAMA KELAS & TINGKAT LAMA
} else {
    $rombelModel->insert($data);
}
```

**Dampak:**
- Jika admin mendownload template rombel lama lalu mengedit isinya (misalnya mengganti nama kelas atau tingkat) namun membiarkan Kolom A (ID) tetap terisi, maka saat diimport kembali sistem akan **meng-update record rombel yang memiliki ID tersebut**.
- Rombel asli akan berubah nama kelas dan tingkatnya secara permanen.

### B. Dampak Penimpaan Rombel terhadap Hilangnya LM Guru
Jika sebuah rombel tingkat 8 (contoh: *Kyanite*) tidak sengaja tertimpa tingkatnya menjadi `7` atau `NULL`, maka:
1. Guru kelas Kyanite akan mencari LM untuk tingkat 7 (bukan tingkat 8).
2. Jika guru menginput nilai pada LM tingkat 7, nilai tersebut tidak akan sinkron dengan indikator rapor tingkat 8.
3. Sebaliknya, jika tingkat berubah ke nilai yang tidak memiliki LM, daftar LM guru akan menjadi kosong.

---

## 5. TEMUAN 4: PENYEBAB SISWA DUPLIKAT & NILAI GAGAL MASUK DI TEMPLATE KOLEKTIF

### A. Siswa Duplikat di Form Download Template
Ditemukan pada kelas Topaz bahwa siswa seperti *Muhammad Afkhar Ayub* dan *Anggana* muncul lebih dari 1 kali:
- **Penyebab:** Pada tabel `anggota_rombel`, satu `siswa_id` terdaftar lebih dari 1 kali di `rombel_id` yang sama karena tabel tersebut sebelumnya tidak memiliki Unique Index `(siswa_id, rombel_id)`.
- Query `getAnggotaRombel()` melakukan `JOIN siswa` tanpa `GROUP BY s.id`, sehingga siswa muncul berulang kali di Excel template nilai.

### B. Nilai Gagal Masuk karena Spasi pada NIS
Pada file `Template_Kolektif_Tengah_Topaz (1).xlsx`:
- Kolom NIS siswa tertentu memiliki karakter spasi di depannya (contoh: `' 242507018'`).
- Script import nilai sebelumnya mencocokkan string secara mutlak: `WHERE s.nis = ?`.
- Nilai dari Excel gagal dipetakan ke siswa karena spasi tersebut membuat pencocokan gagal.

---

## 6. TEMUAN 5: PERBEDAAN PERHITUNGAN NILAI SISTEM VS DKN MANUAL GURU

Berdasarkan perbandingan antara `DKN Sumatif Tengah Semester Topaz.xlsx` dengan hasil sistem:
1. **Perhitungan Manual Guru di Excel DKN:**
   - Guru menghitung Nilai Rapor Tengah Semester murni dari rata-rata Sumatif:
     $$\text{Nilai} = \frac{\text{LM1} + \text{LM2} + \text{LM3}}{3}$$
2. **Perhitungan Otomatis Sistem eRapor:**
   - Sistem menggunakan konfigurasi pembobotan rapor:
     $$\text{Nilai Akhir} = \frac{(\text{Bobot Formatif} \times \text{Rata Formatif}) + (\text{Bobot Sumatif} \times \text{Rata Sumatif})}{\text{Total Bobot}}$$
   - Jika konfigurasi sekolah mengaktifkan Bobot Formatif (misal 50:50) sementara guru belum menginput nilai Formatif (nilai formatif = 0), maka nilai akhir rapor akan terbagi dua (misal dari 97 turun menjadi 48.5 atau 58.7).

---

## 7. TINDAKAN PERBAIKAN YANG TELAH DIIMPLEMENTASIKAN

| No | Modul / File | Perbaikan yang Dilakukan |
|---|---|---|
| 1 | `backend/app/Controllers/Admin/MasterLmController.php` | Menambahkan parser angka Romawi (`VII` $\rightarrow$ 7, `VIII` $\rightarrow$ 8, `IX` $\rightarrow$ 9) dan fallback pencocokan nama rombel aktif agar LM kelas 8 & 9 tidak lagi ter-default ke 7 dan menimpa kelas 7. |
| 2 | `backend/app/Controllers/Admin/SiswaController.php` | Menambahkan filter Tahun Ajaran Aktif (`id_tahun_ajaran = $taAktif['id']`) pada pemetaan nama rombel saat import siswa massal. |
| 3 | `backend/app/Controllers/Admin/SiswaController.php` | Menambahkan **Guard Protection** pada fungsi `delete($id)`. Siswa yang sudah memiliki riwayat nilai formatif, sumatif, atau rapor **dilarang dihapus** untuk mencegah data terhapus tanpa sengaja. |
| 4 | `backend/app/Controllers/GuruMapel/NilaiKolektifController.php` | Menambahkan `TRIM(s.nis)` dan mekanisme fallback pencocokan berdasarkan Nama Lengkap siswa jika NIS gagal dicocokkan. |
| 5 | Root Workspace | Disediakan skrip CLI mandiri [capture_siswa_rombel.php](file:///d:/laragon/htdocs/erapoteasy/capture_siswa_rombel.php) dan [restore_siswa_rombel.php](file:///d:/laragon/htdocs/erapoteasy/restore_siswa_rombel.php) untuk snapshot & restore instan di VPS. |

---

## 8. PANDUAN & REKOMENDASI MITIGASI UNTUK ADMIN/TIM IT

1. **Deploy Perbaikan ke VPS:**
   Upload file yang telah diperbaiki:
   - `backend/app/Controllers/Admin/MasterLmController.php`
   - `backend/app/Controllers/Admin/SiswaController.php`
   - `backend/app/Controllers/GuruMapel/NilaiKolektifController.php`

2. **Jalankan Verifikasi Integritas LM di Database VPS:**
   ```sql
   -- Periksa distribusi tingkat pada master LM
   SELECT tingkat, COUNT(*) as total_lm 
   FROM master_lm 
   GROUP BY tingkat;
   ```
   *Jika tingkat 8 atau 9 berjumlah 0, lakukan import ulang Master LM menggunakan file controller yang sudah diperbaiki.*

3. **Jalankan Verifikasi Rombel di Database VPS:**
   ```sql
   -- Pastikan tidak ada rombel tahun aktif yang tingkatnya tertimpa/salah
   SELECT id, nama_rombel, tingkat, id_tahun_ajaran 
   FROM rombel 
   WHERE id_tahun_ajaran = (SELECT id FROM tahun_ajaran WHERE is_active = 1);
   ```

4. **Jadwalkan Backup Rutin Snapshot Siswa & Rombel:**
   Gunakan cron job di VPS untuk mengeksekusi:
   ```bash
   php /path/to/erapoteasy/capture_siswa_rombel.php
   ```
   sehingga setiap perubahan siswa dan rombel memiliki snapshot JSON harian yang siap di-restore kapan saja tanpa downtime.
