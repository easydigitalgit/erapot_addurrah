# Laporan Analisa dan Perbaikan Master Lingkup Materi (LM) Lintas Tahun Ajaran

**Tanggal:** 20 Oktober 2026  
**Status:** Selesai / Teruji  
**Modul Terdampak:** Master LM Admin, Nilai Kolektif Guru, Nilai Formatif Guru, Monitoring Nilai Admin

---

## 1. Latar Belakang Masalah
Guru mata pelajaran Ilmu Pengetahuan Alam (IPA) melaporkan kendala saat mengunduh file template Excel nilai kolektif tengah semester (STS) untuk kelas 8 dan 9 pada Tahun Ajaran Aktif 2026/2027 Ganjil:
- Kolom nilai yang muncul pada template Excel **hanya NH 1 dan UH 1**.
- Padahal pada halaman **Admin > Master Data LM**, materi untuk IPA kelas 8 dan 9 sudah terdaftar lengkap (memiliki 2 s/d 3 materi LM).

---

## 2. Hasil Investigasi dan Analisis Akar Masalah (Root Cause)

### 2.1. Konsep Desain vs Implementasi Query
Pada tampilan antarmuka Admin Master LM, sistem dirancang secara **Universal (Tanpa Batas Tahun)** agar kurikulum dan capaian pembelajaran dapat digunakan berkelanjutan antar tahun ajaran.

Namun, pada controller pengunduh template (`NilaiKolektifController.php`), terdapat filter query yang mengunci kolom `tahun_ajaran_id`:
```php
if ($this->db->fieldExists('tahun_ajaran_id', 'master_lm')) {
    $bLm->where('tahun_ajaran_id', $ta_id); // Mengunci TA ID aktif saat ini (ID: 10)
}
```

### 2.2. Kronologi Data di Database
1. **Semester Lalu (2025/2026 Genap - TA ID 9):**  
   Pada 4 April 2026, admin mengimpor bank data LM. Data semester genap otomatis tersimpan dengan label `tahun_ajaran_id = 9`. Karena TA aktif saat itu adalah 9, filter query cocok 100% dan tidak ada keluhan dari guru manapun.
2. **Semester Berjalan (2026/2027 Ganjil - TA ID 10):**  
   Data semester ganjil yang diimpor pada April 2026 berlabel `tahun_ajaran_id = 7`. Begitu sekolah masuk ke TA 10, pencarian dengan `tahun_ajaran_id = 10` menghasilkan **0 baris data**.
3. **Mengapa Hanya Guru IPA yang Komplain?**  
   Pemeriksaan sistem membuktikan bahwa **seluruh mata pelajaran lain di sekolah** (PAI, PKN, B. Indo, B. Inggris, IPS, PJOK, Hadist, dll) juga mengalami hal yang sama jika diunduh templatenya. Guru IPA menjadi yang pertama melaporkan karena merupakan guru pertama yang mulai mengunduh template tengah semester ganjil ini.

---

## 3. Implementasi Perbaikan

### 3.1. Menjadikan Tabel `master_lm` Sepenuhnya Lintas Tahun Ajaran (Universal)
1. **Modifikasi Skema Database:**
   ```sql
   ALTER TABLE master_lm MODIFY COLUMN tahun_ajaran_id INT NULL DEFAULT NULL;
   UPDATE master_lm SET tahun_ajaran_id = NULL;
   ```
   Seluruh 334 baris bank materi LM kini berstatus universal (tidak lagi terkekang oleh ID tahun ajaran tertentu).

2. **Penghapusan Penyematan Tahun Ajaran saat Tambah/Impor LM:**
   - Pada file `app/Controllers/Admin/MasterLmController.php`:
     - Method `store()`: Menghilangkan pencarian `$taAktif` dan mengisi `'tahun_ajaran_id' => null`.
     - Method `importExcel()`: Menghilangkan pemetaan otomatis ke `$finalTahunAjaranId` dan menyimpan `'tahun_ajaran_id' => null`.

3. **Penghapusan Filter Tahun Ajaran pada Pengambilan LM:**
   - `app/Controllers/GuruMapel/NilaiKolektifController.php`:
     - Method `downloadTemplate()`: Menghapus `where('tahun_ajaran_id', $ta_id)`.
     - Method `_saveFormatif()`: Menghapus pengecekan kaku `tahun_ajaran_id` saat mengaitkan deskripsi capaian rapor.
     - Penambahan dukungan alias kategori: `['Tengah', 'STS', 'PTS']` dan `['Akhir', 'SAS', 'PAS']`.
   - `app/Controllers/GuruMapel/NilaiFormatifController.php`:
     - Method `_getLmData()`: Menghapus filter `tahun_ajaran_id`.
   - `app/Controllers/Admin/MonitoringNilaiSiswaController.php`:
     - Menghapus filter `tahun_ajaran_id` pada penghitungan jumlah LM dinamis.

---

### 3.2. Penambahan Fitur Manajemen Status LM (Aktif / Nonaktif)

Untuk mencegah kerusakan data historis rapor lama jika suatu materi tidak lagi diajarkan, ditambahkan fitur **Nonaktifkan LM (Soft Delete)**:

1. **Route Baru:**
   - `POST /admin/master-lm/toggle-status` -> `MasterLmController::toggleStatus`

2. **Backend Controller:**
   - Method `toggleStatus()` pada `MasterLmController.php` untuk membalikkan status antara `Aktif` dan `Nonaktif`.
   - Method `update()` mendukung pembaruan status langsung dari modal edit.

3. **Antarmuka Pengguna (UI) Admin:**
   - **Filter Status Baru:** Dropdown filter di halaman Master LM untuk melihat `Semua Status`, `Hanya Aktif`, atau `Hanya Nonaktif`.
   - **Kolom Status di Tabel:** Menampilkan badge pill interaktif (Hijau untuk *Aktif*, Abu-abu untuk *Nonaktif*). Mengklik badge dapat langsung mengubah status tanpa perlu membuka modal edit.
   - **Tombol Aksi Cepat:** Tombol ikon status pada kolom aksi.
   - **Pencegahan Penghapusan Fatal (Safe Delete):** Saat pengguna mengklik tombol "Hapus", sistem menampilkan dialog peringatan SweetAlert2 dengan opsi:
     - `Nonaktifkan Saja (Aman)`: Mengubah status LM menjadi nonaktif agar data rapor masa lalu tetap aman.
     - `Tetap Hapus Permanen`: Hard delete hanya jika admin benar-benar yakin materi belum pernah dinilai.

---

## 4. Hasil Pengujian dan Verifikasi

Simulasi pengujian download template pada Tahun Ajaran Aktif 2026/2027 Ganjil setelah perbaikan:

| Kelas / Rombel | Tingkat | Status LM di Database | Hasil Download Sebelum Perbaikan | Hasil Download Setelah Perbaikan |
| :--- | :---: | :---: | :---: | :---: |
| **VII Amazonite** | 7 | 2 LM Aktif | NH 1 s/d NH 2 & UH 1 s/d UH 2 | **NH 1 s/d NH 2 & UH 1 s/d UH 2** (Tetap Benar) |
| **VIII Safir** | 8 | 2 LM Aktif | HANYA NH 1 & UH 1 ❌ | **NH 1 s/d NH 2 & UH 1 s/d UH 2** ✅ (Lengkap) |
| **IX Emerald** | 9 | 3 LM Aktif | HANYA NH 1 & UH 1 ❌ | **NH 1 s/d NH 3 & UH 1 s/d UH 3** ✅ (Lengkap) |

**Uji Efek Nonaktif:**
- Ketika LM 1 kelas 8 dinonaktifkan (`status = 'Nonaktif'`), template download secara otomatis menyesuaikan hanya membuat 1 kolom aktif.
- Ketika diaktifkan kembali, template kembali menghasilkan 2 kolom lengkap.

---

## 5. Ringkasan File yang Diubah

1. `backend/app/Controllers/Admin/MasterLmController.php`:
   - Store & import menyimpan `tahun_ajaran_id = null`.
   - Menambahkan method `toggleStatus()`.
   - Update method `update()` agar mendukung perubahan status.
2. `backend/app/Controllers/GuruMapel/NilaiKolektifController.php`:
   - Menghapus filter `tahun_ajaran_id` pada `downloadTemplate()` dan `_saveFormatif()`.
   - Menyempurnakan filter kategori (STS/Tengah dan SAS/Akhir) dan memastikan hanya LM `Aktif` yang diambil.
3. `backend/app/Controllers/GuruMapel/NilaiFormatifController.php`:
   - Menghapus filter `tahun_ajaran_id` pada `_getLmData()`.
4. `backend/app/Controllers/Admin/MonitoringNilaiSiswaController.php`:
   - Menghapus filter `tahun_ajaran_id` pada penghitungan LM.
5. `backend/app/Config/Routes.php`:
   - Menambahkan rute `toggle-status`.
6. `backend/app/Views/admin/master-lm/index.php`:
   - Menambahkan filter Status LM.
   - Menambahkan kolom Status pada tabel data.
   - Menambahkan input select Status pada form tambah/edit LM.
7. `backend/public/assets/js/Admin/master-lm.js`:
   - Menambahkan fungsi AJAX `toggleStatus()`.
   - Menambahkan filter dan badge interaktif status.
   - Menambahkan proteksi safe delete (opsi nonaktifkan dibanding hapus permanen).
