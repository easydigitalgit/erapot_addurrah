# Laporan Analisa & Perbaikan Data Siswa Kelas IX Fairuz dan Pemulihan Kolom Data Tergeser
## Berdasarkan File: `docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`

**Tanggal Pemeriksaan:** 21 Oktober 2026  
**Tahun Ajaran Aktif:** 2026/2027 - Ganjil (ID: 10)  
**Rombel Target:** IX Fairuz (ID Rombel: 67)  
**Wali Kelas:** Mohammad Hamsyahri, S.Pd., Gr. (ID: 111)  
**File Query SQL Siap Eksekusi di VPS:** `docs/perbaikan_siswa_kelas_fairuz.sql`

---

## 1. Ringkasan Eksekutif

Menindaklanjuti laporan dari wali kelas **IX Fairuz** (*Ustadz Mohammad Hamsyahri, S.Pd., Gr.*) yang menginformasikan bahwa jumlah siswa di dashboard kelasnya hanya menampilkan **23 siswa** (seharusnya **28 siswa** sesuai rekap dan buku induk Dapodik):

Telah dilakukan audit dan rekonsiliasi data menyeluruh antara file master Dapodik **`docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`** (Sheet *DAFTAR PESERTA DIDIK*, Sheet *REKAP JUMLAH SISWA*, dan Sheet *IX*), backup database `Auto_Full_20261012_130712.sql`, dan database terkini.

### Hasil Rekonsiliasi & Pemulihan:
1. **Jumlah Siswa Aktif Kelas IX Fairuz Genap Kembali Menjadi 28 Siswa (100% Sesuai Dapodik)**.
2. **5 Siswa yang Hilang Berhasil Dipulihkan**:
   - `CHALVIN SURBAKTI` (ID: 165)
   - `DAFFA ARYA GHADZAN` (ID: 167)
   - `EGA PRABUDI` (ID: 168)
   - `ENZO ALFERD ZIDAN` (ID: 169)
   - `EZA SAPITRA ADITYA` (ID: 170)
3. **Catatan Mengenai Siswa ke-29 (`ARFA ADITYA CAHYA` / ID: 162)**:
   Pada tabel `anggota_rombel` terdapat 29 baris, di mana 1 siswa berstatus **`Pindah`** (*Arfa Aditya Cahya*). Sesuai aturan sistem, siswa dengan status `Pindah` tidak dihitung ke kuota aktif Dapodik (sehingga kuota aktif Dapodik tetap tepat **28 siswa**).
4. **Seluruh Riwayat Nilai & Akademik Tersambung Utuh Tanpa Ada Data Nilai yang Hilang**:
   - Masing-masing siswa memiliki **128 penilaian formatif** (total **640 nilai formatif**).
   - Masing-masing siswa memiliki **32 nilai rapor** komponen/mapel (total **160 nilai rapor**).
   - Nilai ekstrakurikuler, catatan rapor, rekap absensi, serta profil orang tua di tabel `orangtua_wali` otomatis terhubung kembali.
5. **Perbaikan Menyeluruh Kolom Data yang Tergeser**:
   - **NIK**: Mengembalikan NIK 16 digit asli dari Dapodik.
   - **Kelurahan**: Mengembalikan nama kelurahan/desa (sebelumnya tertukar terisi nama sekolah asal `SDS...`).
   - **Kecamatan**: Mengembalikan nama kecamatan resmi.
   - **No. HP**: Mengembalikan nomor telepon/HP siswa/wali atau mengosongkannya jika di Dapodik kosong.
6. **Database Lokal Telah Berhasil Diperbarui & Berfungsi 100%**. Script SQL mandiri telah disiapkan di `docs/perbaikan_siswa_kelas_fairuz.sql` untuk langsung dieksekusi di database server VPS.

---

## 2. Temuan Audit & Analisa Akar Masalah (Root Cause Analysis)

### A. Mengapa Siswa Kelas IX Fairuz Berkurang dari 28 Menjadi 23?

Di controller wali kelas (`app/Controllers/WaliKelas/DaftarSiswaController.php`), daftar siswa rombel dipanggil menggunakan query:
```php
$siswa_kelas = $db->table('anggota_rombel ar')
    ->select('siswa.*, users.foto_profil')
    ->join('siswa', 'siswa.id = ar.siswa_id') // <-- INNER JOIN
    ->where('ar.rombel_id', $rombel['id'])
    ->where('siswa.status_siswa', 'Aktif')
    ->get()->getResultArray();
```

Hasil audit basis data menemukan:
* Pada tabel `anggota_rombel` untuk `rombel_id = 67` (IX Fairuz TA 2026/2027 Ganjil), record keanggotaan siswa sebenarnya berjumlah **29 baris** (28 siswa aktif + 1 siswa status `Pindah`).
* Namun, pada tabel master `siswa`, record untuk **5 siswa** (Siswa ID: 165, 167, 168, 169, dan 170) **telah terhapus/hilang**.
* Akibat relasi `INNER JOIN` antara `anggota_rombel` dan `siswa`, ke-5 siswa yang tidak memiliki baris di tabel `siswa` tersebut langsung tereliminasi dari hasil query, sehingga query hanya mengembalikan **23 baris siswa**.
* Karena tabel nilai (`nilai_formatif`, `nilai_rapor`, `nilai_ekskul`, dll.) merujuk langsung ke `siswa_id`, nilai-nilai ke-5 siswa ini sempat menjadi *orphan records*. Dengan mengembalikan baris siswa pada ID aslinya (165, 167, 168, 169, 170), **seluruh data nilai siswa otomatis tersambung kembali seketika tanpa perlu input ulang**.

---

## 3. Rincian 5 Siswa Kelas IX Fairuz yang Dipulihkan

| No | ID Siswa | NIS | NISN | NIK Asli | Nama Siswa | Nilai Formatif | Nilai Rapor | Orang Tua (Ayah / Ibu) | No. HP Ortu |
|:--:|:--:|:--:|:--:|:--:|:---|:--:|:--:|:---|:--:|
| 1 | **165** | 08.24.0035 | 0117135026 | 1271083010110002 | **CHALVIN SURBAKTI** | 128 nilai | 32 nilai | Deden Sopian / Ulfa Yenny | 088262276162 |
| 2 | **167** | 08.24.0038 | 0125883226 | 1271062603130001 | **DAFFA ARYA GHADZAN** | 128 nilai | 32 nilai | - / CITRA RIZDKIKA WULANDARI | 08116000809 |
| 3 | **168** | 08.24.0042 | 0127399258 | 1271201310120001 | **EGA PRABUDI** | 128 nilai | 32 nilai | BUDI ANTO, SH / NINA AFRIANY | 081375600777 |
| 4 | **169** | 08.24.0043 | 0122285018 | 1271140803120001 | **ENZO ALFERD ZIDAN** | 128 nilai | 32 nilai | - / RINI BUDI YANTI | 081325977400 |
| 5 | **170** | 08.24.0046 | 3122288457 | 1271062008120002 | **EZA SAPITRA ADITYA** | 128 nilai | 32 nilai | - / SEPRIYANI | 081260251260 |

---

## 4. Daftar Lengkap 28 Siswa Aktif Kelas IX Fairuz Setelah Perbaikan

| No | ID | NIS | NISN | NIK (16 Digit) | Nama Lengkap | Kelurahan | Kecamatan | No. HP Siswa/Ortu |
|:--:|:--:|:--:|:--:|:--:|:---|:---|:---|:---:|
| 1 | 163 | 08.24.0020 | 0129329730 | 1271021411120001 | Arsya Althaf Abyan | Desa/Kel. Sunggal | Kec. Medan Sunggal | 081215604949 |
| 2 | 164 | 08.24.0027 | 3120665879 | 1271121302120005 | AULIA ARBIE | Desa/Kel. Labuhan Deli | Kec. Medan Marelan | - |
| 3 | 165 | 08.24.0035 | 0117135026 | 1271083010110002 | CHALVIN SURBAKTI | Terjun | Kec. Medan Marelan | 088262276162 |
| 4 | 166 | 10.26.0170 | 0121762297 | 1271062112120004 | DAFFA AHZA DANISH | Desa/Kel. Labuhan Deli | Kec. Medan Marelan | 082273794115 |
| 5 | 167 | 08.24.0038 | 0125883226 | 1271062603130001 | DAFFA ARYA GHADZAN | Titi Papan | Kec. Medan Deli | 08116000809 |
| 6 | 168 | 08.24.0042 | 0127399258 | 1271201310120001 | EGA PRABUDI | Desa/Kel. T E R J U N | Kec. Medan Marelan | 081375600777 |
| 7 | 169 | 08.24.0043 | 0122285018 | 1271140803120001 | ENZO ALFERD ZIDAN | Desa/Kel. Tanah Enam Ratus | Kec. Medan Marelan | 081325977400 |
| 8 | 170 | 08.24.0046 | 3122288457 | 1271062008120002 | EZA SAPITRA ADITYA | Desa/Kel. T E R J U N | Kec. Medan Marelan | 081260251260 |
| 9 | 171 | 10.26.0176 | 3121442308 | 1271120506120001 | FATIH AL HAKIM AZUAR | LABUHAN DELI | Kec. Medan Marelan | 081360052533 |
| 10 | 172 | 08.24.0056 | 3123496851 | 1271123010120008 | Gibran Al Khalifi Zikri | Rengas Pulau | Kec. Medan Marelan | 081396525776 |
| 11 | 173 | 10.26.0199 | 3125848513 | 1271121804120001 | HANIFAN AL WAFI | Desa/Kel. T E R J U N | Kec. Medan Marelan | - |
| 12 | 174 | 08.24.0074 | 0126872877 | 1271130805120003 | MARAFA ANANDA | KLAMBIR | Kec. Hamparan Perak | - |
| 13 | 175 | 08.24.0077 | 3125836474 | 1271132210120001 | MHD ADITHYA SARAGIH | Tangkahan | Kec. Medan Labuhan | - |
| 14 | 176 | 10.26.0223 | 3125095342 | 1271080309120004 | MUHAMMAD AZKAR | Belawan I | Kec. Medan Kota Belawan | - |
| 15 | 177 | 10.26.0250 | 3123754728 | 1271080204120001 | MUHAMMAD DAIROBI ALFATH | Desa/Kel. Belawan Bahagia | Kec. Medan Kota Belawan | 081260454933 |
| 16 | 178 | 10.26.0223 | 3127255237 | 1271122507120001 | MUHAMMAD FAHRI RAMADHAN | Desa/Kel. T E R J U N | Kec. Medan Marelan | 081397788708 |
| 17 | 179 | 10.26.0253 | 0128508311 | 1271200403120001 | MUHAMMAD NALA AL FARIZI | Rengas Pulau | Kec. Medan Marelan | 087805140350 |
| 18 | 180 | 08.24.0089 | 3114761315 | 1271082211110004 | MUHAMMAD RADITYA AZRI | Desa/Kel. Tanah Enam Ratus | Kec. Medan Marelan | 085270735399 |
| 19 | 181 | 08.24.0092 | 0126631828 | 1271200610120001 | MUKHLIS | Terjun | Kec. Medan Marelan | 08989981114 |
| 20 | 182 | 10.26.0247 | 0123577127 | 1271061902120001 | OK. Zabran Rizky | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 081376222609 |
| 21 | 183 | 08.24.0115 | 0129774943 | 1271121909120004 | Qori Al Furqan Tambunan | Rengas Pulau | Kec. Medan Marelan | 088260096791 |
| 22 | 184 | 08.24.0117 | 0123402924 | 1271202203120002 | RAFA EL FATHARA | RENGAS PULAU | Kec. Medan Marelan | 081264283313 |
| 23 | 185 | 10.26.0223 | 0121342803 | 1207253101120002 | RASYA MUHAMMAD ATHAYA | Helvetia | Kec. Labuhan Deli | 081376987699 |
| 24 | 186 | 10.26.0253 | 0124159788 | 1271121207120003 | RAZKA SAQIL ALVARO | PAYA PASIR | Kec. Medan Marelan | 082164058181 |
| 25 | 187 | 08.25.0162 | 3116848505 | 1274040907120001 | RIFKI ZULKARNAIN NASUTION | Pematang Pasir | Kec. Medan Marelan | 082167234984 |
| 26 | 188 | 08.24.0128 | 0129509131 | 1271061501120004 | RIZKY SAPUTRA | KOTA BANGUN | Kec. Medan Deli | 082361390629 |
| 27 | 189 | 08.24.0145 | 0127233999 | 1207262902120015 | ZAHRAN EMRY KADITYA | RENGAS PULAU | Kec. Medan Marelan | 082297306217 |
| 28 | 190 | 08.24.0151 | 0126894063 | 1271120806120001 | ZEIN AHMAD IBRAHIM | Tanah Enam Ratus | Kec. Medan Marelan | 082365698303 |

---

## 5. Panduan Eksekusi Perbaikan di Server VPS

File SQL telah dipersiapkan dan diuji pada direktori:  
📁 **`docs/perbaikan_siswa_kelas_fairuz.sql`**

### Struktur Isi File SQL:
* **Bagian 1**: Query `INSERT INTO siswa` dan `INSERT INTO orangtua_wali` untuk me-restore 5 siswa IX Fairuz (ID 165, 167, 168, 169, 170) dengan klausa aman `ON DUPLICATE KEY UPDATE`.
* **Bagian 2**: Query `UPDATE` kolom tergeser untuk seluruh 28 siswa aktif kelas IX Fairuz.
* **Bagian 3**: Query verifikasi otomatis pasca eksekusi.

---

### Cara Eksekusi di VPS:

#### Opsi A: Melalui SSH Terminal (Paling Cepat & Direkomendasikan)

1. **Login ke Server VPS via SSH**:
   ```bash
   ssh user@ip_vps_anda
   ```

2. **Masuk ke folder proyek**:
   ```bash
   cd /path/to/erapoteasy
   ```

3. **Tarik file SQL terbaru dari repository Git**:
   ```bash
   git pull origin main
   ```
   *(Pastikan file `docs/perbaikan_siswa_kelas_fairuz.sql` sudah masuk).*

4. **Eksekusi file SQL ke database**:
   ```bash
   mysql -u [db_user] -p [db_name] < docs/perbaikan_siswa_kelas_fairuz.sql
   ```
   *(Masukkan password database MySQL Anda saat diminta).*

---

#### Opsi B: Melalui phpMyAdmin di Browser

1. Buka phpMyAdmin di server VPS / cPanel.
2. Pilih database aplikasi rapor (`raporsmpit`).
3. Buka tab **Import**.
4. Klik **Choose File** lalu pilih file `docs/perbaikan_siswa_kelas_fairuz.sql`.
5. Klik tombol **Go / Kirim** di bagian bawah.

---

## 6. Query Verifikasi Pasca-Eksekusi

Setelah query dijalankan di VPS, jalankan perintah verifikasi berikut di tab SQL phpMyAdmin atau MySQL CLI untuk memastikan keberhasilan perbaikan:

```sql
-- 1. Cek Total Siswa Aktif Kelas IX Fairuz (Harus Tepat 28 Siswa)
SELECT COUNT(*) AS total_fairuz 
FROM siswa 
WHERE rombel_id = 67 AND status_siswa = 'Aktif';

-- 2. Cek Total Siswa yang Tampil di Menu Wali Kelas IX Fairuz (Harus Tepat 28 Siswa)
SELECT COUNT(s.id) AS total_tampil_walikelas 
FROM anggota_rombel ar 
JOIN siswa s ON s.id = ar.siswa_id 
WHERE ar.rombel_id = 67 AND s.status_siswa = 'Aktif';

-- 3. Cek 5 Siswa yang Baru Dipulihkan Beserta Nilainya
SELECT 
    s.id, s.nis, s.nisn, s.nama_lengkap, s.nik, s.no_hp, s.kelurahan, s.kecamatan,
    COUNT(DISTINCT nf.id) as jml_nilai_formatif,
    COUNT(DISTINCT nr.id) as jml_nilai_rapor
FROM siswa s 
LEFT JOIN nilai_formatif nf ON nf.siswa_id = s.id 
LEFT JOIN nilai_rapor nr ON nr.siswa_id = s.id 
WHERE s.id IN (165, 167, 168, 169, 170) 
GROUP BY s.id;
```

### Hasil yang Diharapkan:
1. `total_fairuz` = **28**
2. `total_tampil_walikelas` = **28**
3. `jml_nilai_formatif` = **128**, `jml_nilai_rapor` = **32** untuk masing-masing ke-5 siswa yang dipulihkan.
