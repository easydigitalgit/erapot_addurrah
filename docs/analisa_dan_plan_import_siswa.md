# Analisa dan Rencana Perbaikan Fitur Import Data Siswa

Dokumen ini memuat analisa mendalam mengenai kendala import data siswa via Excel, evaluasi alur data Dapodik vs Template e-Rapor, pertimbangan penggunaan NIS sebagai acuan utama, serta rencana implementasi teknis perbaikan.

---

## 1. Analisa Kasus 1: Mengapa Admin Tidak Bisa Langsung Memotong (Cut) File Dapodik Menjadi 17 Kolom Template?

### A. Perbandingan Struktur Kolom
Jika admin mengekspor data mentah dari **Dapodik (66 Kolom: A s.d. BN)** lalu berasumsi bahwa 17 kolom pertama (A s.d. Q) bisa langsung di-*cut* dan diunggah ke e-Rapor, akan terjadi **kerusakan pemetaan data total**.

Berikut adalah perbandingan 17 kolom pertama antara Dapodik dan Template e-Rapor saat ini:

| Kolom | Dapodik Export (66 Kolom) | Template e-Rapor Saat Ini (17 Kolom) | Dampak Jika Admin Cut 17 Kolom Pertama |
| :---: | :--- | :--- | :--- |
| **A** | No. Urut (Angka: 1, 2, 3...) | ID Siswa (Dikosongkan untuk data baru) | Jika kosong, baris di-skip sistem |
| **B** | Nama Peserta Didik | Nama Lengkap | Cocok |
| **C** | NIPD / NIS | NIS | Cocok |
| **D** | **Jenis Kelamin (L/P)** | **NISN** | ❌ **Tertukar**: NISN terisi gender (L/P) |
| **E** | **NISN** | **Email** | ❌ **Tertukar**: Email terisi nomor NISN |
| **F** | **Tempat Lahir** | **Jenis Kelamin (L/P)** | ❌ **Tertukar**: Gender terisi nama kota |
| **G** | **Tanggal Lahir** | **Tempat Lahir** | ❌ **Tertukar**: Tempat lahir terisi tanggal |
| **H** | **NIK** | **Tanggal Lahir** | ❌ **Tertukar**: Tgl lahir gagal diparse |
| **I** | Agama | Agama | Cocok |
| **J** | **Alamat Jalan** | **Anak Ke** | ❌ **Tertukar**: Anak ke terisi nama jalan |
| **K** | **RT** | **Status Dalam Keluarga** | ❌ **Tertukar**: Status keluarga terisi RT |
| **L** | **RW** | **Alamat** | ❌ **Tertukar**: Alamat terisi angka RW |
| **M** | **Nama Dusun** | **No Telp Rumah** | ❌ **Tertukar**: No telp terisi dusun |
| **N** | **Kelurahan / Desa** | **Asal Sekolah** | ❌ **Tertukar**: Asal sekolah terisi kelurahan |
| **O** | **Kecamatan** | **Diterima di Kelas** | ❌ **Tertukar**: Kelas terisi kecamatan |
| **P** | **Kode Pos** | **Tanggal Diterima** | ❌ **Tertukar**: Tgl terima terisi kode pos |
| **Q** | **Jenis Tinggal** | **Status Siswa** | ❌ **Tertukar**: Status siswa terisi 'Bersama orang tua' |

### B. Kolom-Kolom Kunci yang Tercecer di Dapodik
Data penting lainnya pada file Dapodik berada di posisi kolom yang sangat jauh ke kanan:
- **Nama Rombel / Kelas**: Berada di Kolom **AQ (Kolom 43)**
- **Data Ayah, Ibu, Wali**: Berada di Kolom **Y s.d. AP (Kolom 25 - 42)**
- **Sekolah Asal**: Berada di Kolom **BE (Kolom 57)**
- **Anak Ke**: Berada di Kolom **BF (Kolom 58)**

### C. Kesimpulan Kasus 1:
Admin **TIDAK BISA** sekadar memotong 17 kolom pertama Dapodik. Untuk memasukkan data ke template 17 kolom, admin terpaksa melakukan *copy-paste* manual kolom per kolom secara terpisah. Jika admin memotong begitu saja, sistem akan mengalami kegagalan validasi atau data tersimpan dalam kondisi kacau/rusak.

---

## 2. Analisa Kasus 2: Penggunaan NIS vs ID Database Sebagai Acuan Import

### A. Mengapa Menggunakan `ID Database` Kurang Tepat?
1. **Abstrak & Tak Terlihat**: `id` adalah *Auto Increment Primary Key* internal MySQL. Admin sekolah tidak mengetahui angka ID database siswa (tidak tercantum di buku induk, raport fisik, kartu pelajar, maupun file Dapodik).
2. **Rawan Salah Timpa**: Jika admin mengisi angka acak di kolom ID (misal mengisi nomor urut 1, 2, 3), sistem yang berbasis ID akan menimpa siswa ID 1, ID 2, dan ID 3 milik angkatan lain.
3. **Data Baru Selalu Kosong**: Untuk input siswa baru, ID selalu kosong, sehingga kolom ini menjadi mubazir dan membingungkan admin.

### B. Keunggulan Menggunakan `NIS` Sebagai Acuan Utama:
1. **Identitas Unik Resmi Sekolah**: NIS adalah nomor identitas tunggal siswa di lingkungan sekolah.
2. **Mudah Dicari & Divalidasi**: Jika terjadi ketidaksesuaian nilai atau kelas di kemudian hari, admin dan wali kelas selalu merujuk pada NIS.
3. **Logika Upsert (Update or Insert) yang Bersih**:
   - **Jika NIS sudah terdaftar di database**: Sistem melakukan **UPDATE** data siswa tersebut (biodata, kelas, alamat, dsb.).
   - **Jika NIS belum terdaftar di database**: Sistem melakukan **INSERT** siswa baru, sekaligus otomatis membuatkan akun login (`users`) dan menetapkan rombel di tahun ajaran aktif.
   - **Jika NIS sengaja dikosongkan pada baris baru**: Sistem dapat meng-generate NIS otomatis menggunakan format resmi sekolah (`generateNextNis`).

---

## 3. Rencana Solusi & Perbaikan (Implementation Plan)

### Rencana 1: Format Template Standar e-Rapor Berbasis NIS
Template download di e-Rapor disempurnakan dengan urutan kolom yang logis dan jelas:

| No | Nama Kolom Header | Wajib / Opsional | Keterangan |
| :---: | :--- | :---: | :--- |
| **A** | `NIS (Kunci Utama)` | **Wajib / Acuan** | Jika NIS sudah ada = Update, Jika belum = Tambah Baru |
| **B** | `NISN` | Opsional | 10 Digit Angka |
| **C** | `NIK` | Opsional | 16 Digit Angka |
| **D** | `Nama Lengkap` | **Wajib** | Nama lengkap santri / siswa |
| **E** | `Jenis Kelamin` | **Wajib** | `L` atau `P` |
| **F** | `Tempat Lahir` | Opsional | Nama Kota / Kabupaten |
| **G** | `Tanggal Lahir` | Opsional | Format `YYYY-MM-DD` (contoh: 2010-05-15) |
| **H** | `Agama` | Opsional | Default: `Islam` |
| **I** | `Kelas / Rombel` | **Penting** | Contoh: `VII A`, `VIII 1`, `IX Safir` (Otomatis masuk rombel) |
| **J** | `Alamat Lengkap` | Opsional | Jalan, Dusun, RT/RW |
| **K** | `Kecamatan` | Opsional | Nama Kecamatan |
| **L** | `Kelurahan / Desa` | Opsional | Nama Desa / Kelurahan |
| **M** | `No HP Siswa / WA` | Opsional | Nomor kontak santri |
| **N** | `Email Siswa` | Opsional | Jika kosong, otomatis dibuatkan sistem |
| **O** | `Nama Ayah` | Opsional | Nama Ayah Kandung |
| **P** | `Nama Ibu` | Opsional | Nama Ibu Kandung |
| **Q** | `No HP / WA Orang Tua`| Opsional | Kontak orang tua untuk notifikasi rapor |
| **R** | `Status Siswa` | Opsional | `Aktif` / `Lulus` / `Pindah` / `Keluar` (Default: Aktif) |

### Rencana 2: Smart Auto-Detection Engine pada `import()`
Fitur import di `SiswaController.php` dibuat cerdas sehingga **bisa menerima kedua jenis file**:
1. **Tipe A - Template Standar e-Rapor (18 Kolom)**:
   - Dideteksi jika Header Kolom A berisi `NIS` atau total kolom <= 25.
   - Menggunakan NIS sebagai acuan update/insert.
2. **Tipe B - File Mentah Ekspor Dapodik Asli (66 Kolom)**:
   - Dideteksi jika sheet berisi `daftar peserta didik` atau kolom > 40.
   - Otomatis membaca posisi asli Dapodik (Nama di B, NIS di C, JK di D, NISN di E, Rombel di AQ, dsb.) tanpa menuntut admin memotong file.

### Rencana 3: Logika Sinkronisasi Mesin Waktu & User Account
Saat import dieksekusi (baik dari Template e-Rapor maupun Dapodik):
1. **User Login (`users`)**:
   - Jika siswa baru, otomatis buat akun user dengan `username = NIS` (atau NISN) dan password default `12345678`.
2. **Rombel Aktif (`anggota_rombel`)**:
   - Jika kolom Rombel/Kelas terisi dan cocok dengan master rombel di database, siswa otomatis ditempatkan ke rombel tersebut pada tahun ajaran & semester aktif.
3. **Data Orang Tua (`orangtua_wali`)**:
   - Terintegrasi langsung dengan `siswa_id` yang bersangkutan.

---

## 4. Matriks Validasi & Penanganan Error

| Skenario | Tindakan Sistem |
| :--- | :--- |
| NIS sudah ada di DB | Lakukan update profil & kelas tanpa membuat duplikasi user login |
| NIS baru & belum ada di DB | Lakukan insert siswa baru + buat akun user + masukkan ke rombel aktif |
| NIS kosong tapi Nama ada | Generate NIS otomatis berdasarkan nomor urut angkatan tahun berjalan |
| Format tanggal lahir `DD/MM/YYYY` atau `YYYY-MM-DD` | Parse otomatis ke format database MySQL `YYYY-MM-DD` |
| Rombel tidak ditemukan di DB | Siswa tetap disimpan sebagai data mandiri (Status: Belum punya rombel) + catat info di log |

---

## 5. Ringkasan Tindakan Teknis
1. Memperbarui fungsi `downloadTemplate()` di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php) dengan template 2 sheet berbasis NIS (Sheet 1: Form Data Siswa 21 kolom, Sheet 2: Referensi Rombel & Kelas Aktif).
2. Memperbarui fungsi `import()` di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php) dengan pembacaan berbasis NIS (Upsert), penanganan tanggal multi-format (Excel serial & string), pembuatan akun login siswa & ortu otomatis, serta penempatan rombel aktif (`anggota_rombel`).
3. Menguji coba file sampel import dan parsing cell.

---

## 6. Laporan Hasil Implementasi & Pengujian

### A. Perubahan yang Telah Diterapkan
1. **Template Excel Siswa Baru (`downloadTemplate`)**:
   - **Sheet 1 (`Data Siswa`)**: Terdiri dari 21 kolom terstruktur (A s.d. U) yang dipimpin oleh **NIS** sebagai kunci utama. Tipe data nomor (NIS, NISN, NIK, No HP) diformat eksplisit sebagai teks (String) agar tidak rusak menjadi angka eksponensial di Excel.
   - **Sheet 2 (`Referensi Rombel`)**: Berisi daftar seluruh nama rombel dan tingkat yang aktif di database sekolah agar admin tinggal copy-paste ke Sheet 1 Kolom I.
2. **Mesin Import Siswa (`import`)**:
   - **Matching Berbasis NIS**: Jika NIS terisi dan ada di DB &rarr; UPDATE biodata dan sinkronkan rombel. Jika belum ada &rarr; INSERT siswa baru.
   - **Auto-Generate NIS**: Jika NIS dikosongkan untuk siswa baru, sistem otomatis membuatkan NIS resmi (`generateNextNisInternal`).
   - **User Login Generator**: Otomatis membuat akun siswa (`role_id = 3`, username = NIS, password = `12345678`) dan akun orang tua (`role_id = 4`).
   - **Mesin Waktu Rombel**: Otomatis memasukkan siswa ke tabel `anggota_rombel` pada tahun ajaran dan semester yang sedang aktif.

### B. Hasil Pengujian Unit (Siswa)
Pengujian dilakukan menggunakan script verifikasi [scratch/test_import_siswa.php](file:///d:/laragon/htdocs/erapoteasy/scratch/test_import_siswa.php):
- **Generate Template**: Berhasil membuat file `.xlsx` dengan 2 sheet dan baris sampel.
- **Parsing Data Baris**: Seluruh 21 kolom berhasil dipetakan secara akurat tanpa pergeseran indeks.
- **Status Eksekusi**: `Exit Code 0 (Success)`.

---

## 7. Analisa & Implementasi Modul Import Data Orang Tua / Wali

### A. Temuan Masalah Sebelumnya di Modul Orang Tua
1. **Ketidakcocokan Kolom Fatal Antara Template vs Import**:
   - `downloadTemplate()` lama membuat template 11 kolom dengan Kolom C = `Nama Ayah`.
   - `import()` lama membaca format 12 kolom dengan asumsi Kolom C = `NIS Siswa`.
   - **Dampak Fatal**: Sistem menganggap Nama Ayah (misal: *"Budi Santoso"*) sebagai NIS siswa. Pencarian siswa selalu bernilai `null` sehingga **seluruh baris data orang tua selalu dilewati / gagal disimpan**.
   - Kolom `Nama Ayah` terisi Pekerjaan, `Pekerjaan Ayah` terisi Nama Ibu, dan seterusnya.
2. **Permintaan ID Database Siswa**:
   - Template lama meminta `ID Siswa (WAJIB ADA - Lihat DB Siswa)` di kolom B yang tidak dipahami oleh admin.

### B. Solusi yang Diterapkan di [OrangTuaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/OrangTuaController.php)
1. **Penyelarasan Template 2 Sheet Berbasis NIS (`downloadTemplate`)**:
   - **Sheet 1 (`Data Orang Tua`)**: 13 kolom terstruktur:
     `NIS Siswa (Wajib / Kunci Relasi)` | `Nama Siswa` | `Nama Ayah` | `NIK Ayah` | `Pekerjaan Ayah` | `Nama Ibu` | `NIK Ibu` | `Pekerjaan Ibu` | `Nama Wali` | `Pekerjaan Wali` | `No HP / WA Orang Tua` | `Email Orang Tua` | `Alamat Lengkap`
   - **Sheet 2 (`Daftar Siswa`)**: Menampilkan daftar seluruh siswa aktif beserta NIS dan Kelasnya agar admin mudah melakukan copy-paste NIS.
2. **Logika Import & Upsert Berbasis NIS (`import`)**:
   - Membaca NIS di Kolom A (dengan fallback nama siswa di Kolom B).
   - Menghubungkan orang tua dengan `siswa_id` yang sesuai di database.
   - Otomatis membuat atau menghubungkan akun login orang tua (`role_id = 4`, username = No HP / `W`+NIS, password `12345678`).
   - Melakukan update jika data wali untuk santri tersebut sudah ada, atau insert baru jika belum ada.
3. **Penyelarasan Kolom Export (`export`)**:
   - Struktur kolom file export disamakan 100% dengan template import (13 kolom), sehingga mendukung siklus ekspor &rarr; edit di Excel &rarr; import kembali.

### C. Hasil Pengujian Unit (Orang Tua)
Pengujian dijalankan via script [scratch/test_import_orangtua.php](file:///d:/laragon/htdocs/erapoteasy/scratch/test_import_orangtua.php):
- **Generate Template**: Berhasil membuat file `.xlsx` dengan 2 sheet dan format sel teks yang aman.
- **Parsing Data Baris**: 13 kolom terpetakan dengan presisi tanpa kesalahan pergeseran kolom.
- **Status Eksekusi**: `Exit Code 0 (Success)`.

---

## 8. Analisa & Penanganan Laporan Error: Duplicate Entry Email Siswa & User

### A. Laporan Kasus dari Lapangan
Pada saat pengujian import di server (`raporsmpit.fath.dev/admin/siswa`), muncul modal error:
```text
Proses import gagal:
Baris 2 (Al Buqhori Rafif): Gagal menyimpan data siswa Al Buqhori Rafif: Duplicate entry '1' for key 'siswa.email_siswa'
Baris 3 (Alvaro Rafiqi Iranda Nst): Gagal menyimpan data siswa Alvaro Rafiqi Iranda Nst: Duplicate entry '2' for key 'siswa.email_siswa'
Baris 4 (ARKA ZEVAN ABADI): Gagal menyimpan data siswa ARKA ZEVAN ABADI: Duplicate entry '3' for key 'siswa.email_siswa'
Baris 5 (ARKAAN KAHFI AL IKHSAN): Gagal menyimpan data siswa ARKAAN KAHFI AL IKHSAN: Duplicate entry '4' for key 'siswa.email_siswa'
Baris 6 (AZKA ADHYASTHA): Gagal menyimpan data siswa AZKA ADHYASTHA: Duplicate entry '5' for key 'siswa.email_siswa'
Baris 7 (Bahri Habibie): Gagal membuat akun user untuk Bahri Habibie: Duplicate entry '6' for key 'users.email'
Baris 8 (BIMA ADI BRATA): Gagal membuat akun user untuk BIMA ADI BRATA: Duplicate entry '7' for key 'users.email'
Baris 9 (DAFFA AL RIANSYAH HARAHAP): Gagal membuat akun user untuk DAFFA AL RIANSYAH HARAHAP: Duplicate entry '8' for key 'users.email'
Baris 10 (EMERY ALDRIC MUSYAFFA): Gagal membuat akun user untuk EMERY ALDRIC MUSYAFFA: Duplicate entry '9' for key 'users.email'
```

### B. Akar Penyebab Masalah (Root Cause Analysis)
1. **Pengisian Angka Urut / Absen pada Kolom Email**:
   - Kolom email (Kolom N) diisi angka nomor urut (`1, 2, 3, 4, 5...`) oleh admin sekolah atau terjadi pergeseran kolom saat pengisian Excel.
2. **Constraint `UNIQUE` pada Database**:
   - Kolom `email_siswa` di tabel `siswa` dan `email` di tabel `users` memiliki indeks `UNIQUE`.
   - Pada kode sebelumnya, nilai di kolom N langsung disimpan mentah ke database tanpa validasi apakah format string tersebut adalah email yang sah.
   - Ketika proses import sebelumnya gagal di tengah jalan atau dilakukan import ulang kelas lain yang juga memiliki nomor urut `1, 2, 3...`, MySQL menolak penyimpanan karena terjadi duplikasi nilai `'1'`, `'2'`, `'3'`.
3. **Terbentuknya Akun "Hantu" (Ghost Users)**:
   - Pada alur lama, akun user di tabel `users` dibuat terlebih dahulu sebelum data siswa disimpan.
   - Ketika penyimpanan tabel `siswa` gagal (misal pada Baris 7), akun di tabel `users` tetap tersimpan. Pada percobaan import berikutnya, Baris 7 langsung gagal di tahap pembuatan user dengan pesan `Duplicate entry '6' for key 'users.email'`.

### C. Solusi & Perbaikan Teknis yang Diterapkan
Perbaikan telah diterapkan langsung di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php) dan [OrangTuaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/OrangTuaController.php):

1. **Pemetaan Kolom Dinamis (Dynamic Header Mapping)**:
   - Sistem kini membaca baris header (Baris 1) untuk mencocokkan kata kunci seperti `nis`, `nama`, `rombel`, `email`, dll.
   - Jika admin menyisipkan kolom baru (seperti kolom nomor) atau urutan kolom bergeser, sistem tetap membaca data dari kolom yang tepat sesuai judul headernya.
2. **Sanitasi Format Email Valid (`FILTER_VALIDATE_EMAIL`)**:
   - Nilai email kini divalidasi secara ketat menggunakan fungsi bawaan PHP:
     ```php
     if (!empty($emailRaw) && filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) { ... }
     ```
   - Nilai bukan email (seperti angka urut `'1'`, `'2'`, tanda strip `'-'`, atau teks acak) otomatis diubah menjadi `NULL`.
   - Dalam standar SQL / MySQL, indeks `UNIQUE` memperbolehkan multiple nilai `NULL`, sehingga tidak akan pernah memicu tabrakan `Duplicate entry`.
3. **Pemeriksaan Keunikan Email Sebelum Insert (Pre-Check Uniqueness)**:
   - Sebelum menyisipkan email ke akun user atau data siswa, sistem memeriksa apakah email tersebut sudah terdaftar oleh pengguna/siswa lain:
     - Jika sudah terdaftar oleh entitas lain &rarr; email diabaikan (diset `NULL`) agar proses pembuatan siswa dan akun login tetap berjalan lancar tanpa mengalami crash.
4. **Pembersihan Otomatis Akun Gagal (Cleanup Ghost User)**:
   - Ditambahkan blok penanganan khusus saat terjadi kegagalan insert siswa: jika pembuatan siswa gagal, akun user yang baru saja dibuat untuk baris tersebut langsung dihapus kembali (`$db->table('users')->where('id', $createdUserId)->delete()`).

### D. Rekomendasi Pembersihan Database (Opsional untuk Server Production)
Jika di server database saat ini sudah terdapat record kotor yang berisi email `'1'`, `'2'`, dst., admin/pengembang dapat menjalankan query pembersihan sederhana:
```sql
UPDATE siswa SET email_siswa = NULL WHERE email_siswa REGEXP '^[0-9]+$';
UPDATE users SET email = NULL WHERE email REGEXP '^[0-9]+$';
```
Query di atas mengosongkan email yang hanya berupa deretan angka sehingga database kembali bersih.

### E. Penyelarasan Label Header Template
Untuk memastikan admin tidak lagi salah paham mengira kolom email wajib atau mengisinya dengan nomor urut:
1. **Header Kolom Email Siswa** diperjelas menjadi:
   `Email Siswa (Opsional - Kosongkan Jika Tidak Ada)` pada Kolom N.
2. **Header Kolom Email Orang Tua** diperjelas menjadi:
   `Email Orang Tua (Opsional - Kosongkan Jika Tidak Ada)` pada Kolom L.
3. **Contoh Nilai Sel (Row 2)**:
   Diisi contoh email yang jelas (`ahmad@sekolah.sch.id` dan `budi.santoso@email.com`), bukan teks ambigu.
4. **Proteksi Ganda**:
   Sekalipun admin tetap sengaja/tidak sengaja memasukkan nomor urut, `filter_var(..., FILTER_VALIDATE_EMAIL)` akan otomatis mengabaikannya dan menjadikannya `NULL` tanpa memicu crash database.

---

## 9. Laporan Analisa Kasus Khusus: Terbelahnya Siswa Kelas VII Carnelian (26 vs 7 Siswa) & Error "NISN Sudah Digunakan"

### A. Laporan Kasus dari Lapangan
1. **Keluhan Wali Kelas**:
   - Wali kelas VII Carnelian (**Tika Yeardila, M.Pd.**) mendapati bahwa dari total **33 siswa**, hanya **26 siswa** yang muncul di halaman perwalian wali kelas.
   - Sebanyak **7 siswa** tidak muncul di daftar kelas perwalian:
     1. Ariqa Erita Chaniago
     2. Ashika Zalika zahra tarigan
     3. Ataya Nur Ariqa
     4. Jihan Talita Ulfa
     5. Keisha Amila Husna
     6. Nadifa Talita Sakti
     7. Qabsha Nayla
2. **Kendala Saat Upload Ulang**:
   - Ketika wali kelas mencoba mengunggah / mengimpor kembali file Excel yang berisi 33 siswa tersebut, sistem menampilkan error: **"NISN sudah digunakan siswa lain"**.
3. **Kondisi di Akun Admin**:
   - Jika dicek dari akun Admin pada menu **Data Siswa** (`http://localhost:8080/admin/siswa`), data ke-7 siswa tersebut **ADA**, berstatus **Aktif**, dan tertulis jelas berada di kelas **VII - Carnelian** dengan wali kelas **Tika Yeardila, M.Pd.**

---

### B. Investigasi & Akar Penyebab Masalah (Root Cause Analysis)

#### 1. Temuan Utama: Ada 2 Master Rombel "Carnelian" di Database (ID 55 vs ID 80)
Pemeriksaan langsung ke tabel master `rombel` mengungkap adanya **dua baris data master untuk kelas VII Carnelian**:

| ID Rombel | Nama Rombel | Tingkat | Tahun Ajaran (`id_tahun_ajaran`) | Wali Kelas | Status / Keterangan | Jumlah Siswa |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **80** | **Carnelian** | **VII** | **10 (2026/2027 Ganjil - Aktif)** | Tika Yeardila, M.Pd | Rombel Resmi Tahun Ajaran Aktif | **26 Siswa** |
| **55** | **Carnelian** | **VII** | **NULL (Tanpa Tahun Ajaran)** | Tika Yeardila, M.Pd | Rombel Duplikat / Hantu | **7 Siswa** |

> **Total Siswa Keseluruhan**: 26 + 7 = **33 Siswa**. Seluruh 33 siswa sebenarnya sudah tersimpan di database sekolah.

#### 2. Mengapa Walikelas Hanya Melihat 26 Siswa?
Pada modul Wali Kelas ([DaftarSiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/DaftarSiswaController.php#L52-L68)):
- Sistem mencari rombel perwalian Bu Tika yang memiliki `id_tahun_ajaran = 10` (Tahun Ajaran Aktif).
- Sistem menemukan **Rombel ID 80**.
- Kemudian sistem mengambil daftar siswa anggota rombel dengan query:
  ```sql
  SELECT s.* FROM anggota_rombel ar 
  JOIN siswa s ON s.id = ar.siswa_id 
  WHERE ar.rombel_id = 80 AND ar.tahun_ajaran_id = 10 AND s.status_siswa = 'Aktif';
  ```
- Karena ke-7 siswa tercatat di `rombel_id = 55`, mereka **terfilter keluar** dan tidak tampil di halaman perwalian wali kelas.

#### 3. Mengapa di Akun Admin Data Siswa Terlihat Normal?
Pada modul Admin Data Siswa ([SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php#L85-L105)):
- Query admin mengambil seluruh siswa dan men-*join* ke tabel `rombel` berdasarkan `COALESCE(ar.rombel_id, s.rombel_id)`.
- Ke-7 siswa tersebut men-*join* **Rombel ID 55**.
- Karena Rombel ID 55 namanya sama-sama `"Carnelian"` dan wali kelasnya sama-sama `"Tika Yeardila, M.Pd."`, maka antarmuka admin menampilkan teks yang identik: **VII - Carnelian | Tika Yeardila, M.Pd.**
- Hal ini menimbulkan ilusi bahwa data sudah benar di sisi admin, padahal ID rombel di balik layarnya berbeda (55 vs 80).

#### 4. Mengapa 7 Siswa Bisa Nyasar ke Rombel 55 (Audit Jejak Waktu / Timestamp)?
Pemeriksaan log waktu pembuatan data (`created_at`) di database membuktikan:
```text
1. [2026-10-08 09:48:03] Siswa: AENCHI JIANDRA NABABAN       -> Rombel ID 80
2. [2026-10-08 10:04:26] Siswa: Anggitta Lady Azzahra Siregar -> Rombel ID 80
3. [2026-10-08 10:30:17] Siswa: ARIQA ERITA CHANIAGO          -> Rombel ID 55 (Tersasar)
4. [2026-10-08 10:40:34] Siswa: ASHIKA ZALIKA ZAHRA TARIGAN   -> Rombel ID 55 (Tersasar)
5. [2026-10-08 10:50:17] Siswa: Ashillah Ratu Balqis          -> Rombel ID 80
6. [2026-10-08 11:00:39] Siswa: ATAYA NUR ARIKA              -> Rombel ID 55 (Tersasar)
...
13. [2026-10-09 08:02:09] Siswa: JIHAN TALITA ULFA            -> Rombel ID 55 (Tersasar)
...
16. [2026-10-09 08:29:21] Siswa: KEISHA AMILA HUSNA           -> Rombel ID 55 (Tersasar)
...
20. [2026-10-09 09:13:34] Siswa: NADIFA TALITA SAKHI          -> Rombel ID 55 (Tersasar)
...
24. [2026-10-09 09:40:17] Siswa: QABHSA NAYLA                 -> Rombel ID 55 (Tersasar)
...
33. [2026-10-09 10:42:41] Siswa: YUMNA IZZATUNNISA            -> Rombel ID 80
```
- **Fakta 1**: Ke-33 siswa ini **bukan diimport dalam satu kali transaksi Excel**, melainkan dibuat pada rentang tanggal 8 s.d. 9 Oktober dengan selisih waktu 8–20 menit per siswa (pola pengisian manual satu per satu dari formulir web).
- **Fakta 2 (Akar Bug Dropdown)**:
  Pada fungsi `getRombel()` di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php#L112-L121), query mengambil semua data rombel tanpa memfilter tahun ajaran aktif:
  ```php
  $data = $db->table('rombel')->select('id, nama_rombel, tingkat')->get()->getResultArray();
  ```
  Di elemen `<select id="rombel_id">` pada browser, muncul **dua pilihan dengan nama yang kembar identik**:
  ```html
  <option value="55">VII - Carnelian</option>   <!-- Pilihan ID 55 (Tanpa TA) -->
  <option value="80">VII - Carnelian</option>   <!-- Pilihan ID 80 (TA Aktif) -->
  ```
  Karena kedua label tertulis sama persis `"VII - Carnelian"`, penginput data tidak dapat membedakan keduanya:
  - 7 siswa tidak sengaja terpilih opsi atas (`value="55"`).
  - 26 siswa terpilih opsi bawah (`value="80"`).

#### 5. Mengapa Muncul Error "NISN Sudah Digunakan"?
- Kolom `nisn` pada tabel `siswa` MySQL memiliki aturan `UNIQUE KEY (nisn)`.
- Karena wali kelas mendapati kelasnya hanya berisi 26 siswa, wali kelas mencoba mengunggah ulang file Excel yang berisi seluruh 33 siswa.
- Ketika sistem membaca 7 siswa yang hilang tersebut, sistem mencoba melakukan `INSERT INTO siswa`.
- MySQL langsung menolak insert tersebut karena nomor NISN mereka sudah terdaftar di database sejak 8-9 Oktober, sehingga memunculkan pesan:
  ```text
  Duplicate entry '0142996320' for key 'siswa.nisn' / NISN sudah digunakan siswa lain.
  ```

---

### C. Temuan Kasus Serupa di Kelas Lain
Audit menyeluruh pada rombel duplikat tanpa tahun ajaran menemukan kasus yang sama pada **Kelas VII Ruby**:
- **Rombel ID 56** (Ruby tanpa TA): Terdapat 1 siswa tersasar, yaitu **Akifah Tihani Zuyyin** (ID: 563).
- **Rombel ID 81** (Ruby TA 10 Aktif): Saat ini masih kosong (0 siswa).

---

### D. Rencana Solusi & Perbaikan Menyeluruh (Action Plan)

#### 1. Perbaikan Data Langsung (Database Data Migration)
Menyatukan kembali data siswa yang tersasar ke rombel resmi tahun ajaran aktif:

```sql
-- ========================================================
-- 1. PINDAHKAN 7 SISWA CARNELIAN DARI ROMBEL 55 KE ROMBEL 80
-- ========================================================
UPDATE siswa 
SET rombel_id = 80 
WHERE rombel_id = 55;

UPDATE anggota_rombel 
SET rombel_id = 80 
WHERE rombel_id = 55;

-- ========================================================
-- 2. PINDAHKAN 1 SISWA RUBY DARI ROMBEL 56 KE ROMBEL 81
-- ========================================================
UPDATE siswa 
SET rombel_id = 81 
WHERE rombel_id = 56;

UPDATE anggota_rombel 
SET rombel_id = 81 
WHERE rombel_id = 56;

-- ========================================================
-- 3. BERSIHKAN MASTER ROMBEL HANTU (ID 52 - 66 TANPA TAHUN AJARAN)
-- ========================================================
DELETE FROM rombel 
WHERE (id_tahun_ajaran IS NULL OR id_tahun_ajaran = 0)
  AND id NOT IN (SELECT DISTINCT rombel_id FROM siswa WHERE rombel_id IS NOT NULL);
```

#### 2. Perbaikan Sistem & Logika Kode Backend (System Prevention Fix)

##### A. Perbaikan Dropdown Rombel di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php)
Modifikasi method `getRombel()` agar hanya mengembalikan rombel yang berada pada **Tahun Ajaran Aktif**:
```php
public function getRombel()
{
    $db = \Config\Database::connect();
    
    // Ambil ID Tahun Ajaran Aktif
    $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
    $taId = $taAktif ? $taAktif['id'] : 0;

    $data = $db->table('rombel')
        ->select('id, nama_rombel, tingkat')
        ->where('id_tahun_ajaran', $taId) // 🛡️ Kunci hanya TA aktif!
        ->orderBy('tingkat', 'ASC')
        ->orderBy('nama_rombel', 'ASC')
        ->get()->getResultArray();

    return $this->response->setJSON($data);
}
```

##### B. Perbaikan Mapping Rombel pada Fungsi `import()` di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php)
Pastikan pencocokan nama rombel dari file Excel hanya memetakan ke rombel tahun ajaran aktif:
```php
// 2. Siapkan Mapping Rombel Khusus Tahun Ajaran Aktif
$taAktifId = $taAktif ? $taAktif['id'] : 0;
$dbRombel = $db->table('rombel')
    ->where('id_tahun_ajaran', $taAktifId) // 🛡️ Hanya ambil rombel aktif
    ->get()->getResultArray();

$mapRombel = [];
foreach ($dbRombel as $r) {
    $nama = strtolower(trim($r['nama_rombel']));
    $tingkat = strtolower(trim($r['tingkat']));

    $mapRombel[$tingkat . ' ' . $nama] = $r['id'];
    $mapRombel[$tingkat . '-' . $nama] = $r['id'];
    $mapRombel[$tingkat . $nama] = $r['id'];
    $mapRombel[$nama] = $r['id'];
}
```

---

### E. Hasil yang Dicapai Setelah Perbaikan
1. **Daftar Siswa Kelas Carnelian Lengkap 33 Siswa**: Wali kelas Tika Yeardila akan langsung melihat seluruh 33 santri/siswa lengkap di halaman kelas perwaliannya tanpa perlu mengunggah ulang file Excel.
2. **Eliminasi Error Duplikasi NISN**: Tidak ada lagi kegagalan import akibat tabrakan NISN.
3. **Dropdown Bersih Tanpa Opsi Kembar**: Admin dan wali kelas tidak akan pernah lagi salah memilih rombel hantu karena dropdown hanya memuat rombel tahun ajaran aktif.





