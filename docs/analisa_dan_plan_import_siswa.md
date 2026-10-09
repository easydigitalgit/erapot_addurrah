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


