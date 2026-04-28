# Analisa Struktur Aplikasi ErapotEasy

Berdasarkan eksplorasi pada repositori `erapoteasy`, aplikasi ini mengimplementasikan arsitektur *decoupled* yang memisahkan bagian antarmuka pengguna (*Frontend*) dengan sistem logika *server* (*Backend*).

Berikut adalah rincian stack teknologi dan temuan yang ada:

## 1. Frontend (Vue 3 + Vite)
- **Lokasi Direktori:** `/frontend`
- **Framework Utama:** Vue.js (versi 3.x)
- **Build Tool:** Vite (Sangat optimal dan cepat untuk proses *development* maupun *building*)
- **Styling:** Menggunakan Tailwind CSS, dikonfigurasi menggunakan PostCSS dan Autoprefixer.
- **Package Manager:** npm (`package.json`)
- **Sistem Menjalankan Server Lokal:** `npm run dev`

## 2. Backend (CodeIgniter 4)
- **Lokasi Direktori:** `/backend`
- **Framework Utama:** CodeIgniter 4 (PHP)
- **Struktur:** Memiliki struktur bawaan CI4 seperti folder `app`, `public`, `writable`, dan file *command-line* `.env` serta `spark`.
- **Package Manager:** Composer (`composer.json`)
- **Sistem Menjalankan Server Lokal:** `php spark serve`

## 3. Komponen Tambahan (Root Project)
- **Library Tambahan:** Terdapat `composer.json` di level *root* yang memuat library `phpoffice/phpspreadsheet`. Hal ini sangat relevan mengingat aplikasi ini adalah *Erapot* (Rapor Elektronik) yang sering membutuhkan fitur ekspor/impor dari data file Excel atau CSV (terbukti juga dari beberapa file template `.xls` dan `.csv` di dalam `/docs`).
- **File Database Migrasi:** Pada folder *root* juga tersedia file berjenis `.sql` (`kabupaten.sql`, `kecamatan.sql`, `propinsi.sql`, `raporsmpit.sql`) yang digunakan sebagai sumber *dumping* database awal (*seed/migration*).

---

## Integrasi VS Code (`tasks.json`)
Untuk mempermudah alur kerja pengembangan, saya telah menambahkan konfigurasi VS Code Task (`.vscode/tasks.json`) ke dalam proyek Anda. Anda sekarang dapat menjalankan server dengan langkah yang jauh lebih mudah di VS Code (`Ctrl + Shift + P` -> `Tasks: Run Build Task` atau `Tasks: Run Task`).

Task yang ditambahkan:
1. **Serve Backend (CodeIgniter)**: Menjalankan backend server.
2. **Serve Frontend (Vue/Vite)**: Menjalankan frontend development server.
3. **Serve Application (All)**: Task komposit (*Build Task Default*) yang akan menjalankan frontend dan backend server secara paralel secara otomatis.

---

## Log Perbaikan & Penyesuaian

### 1. Sinkronisasi Filter Mata Pelajaran pada Rapor (Admin vs Wali Kelas)
- **Masalah:** Mata pelajaran yang tercetak di rapor memiliki perbedaan daftar (jumlah) antara tampilan cetak melalui Admin dan preview cetak melalui Wali Kelas.
- **Penyebab:** Terdapat perbedaan pada algoritma filter eksklusi (pengecualian) mata pelajaran antara Admin dan Wali Kelas. Admin mengecualikan kata kunci `tahfidz`, `tahfiz`, `tahsin`, dan `bpi`, sedangkan di *controller* Wali Kelas kata kunci `bpi` tidak dimasukkan ke dalam daftar *exclude*.
- **Solusi:** Menyamakan referensi filter mata pelajaran di Wali Kelas dengan referensi filter pada Admin.
- **File yang Diubah:**
  - `backend/app/Controllers/WaliKelas/PreviewRaporController.php` (menambahkan `'bpi'` pada array `$kata_kunci_kecuali` baris ~289).

### 2. Penambahan Fitur Nomor Urut Mata Pelajaran untuk Pengurutan Rapor
- **Tujuan:** Memungkinkan Admin mengatur urutan tampilan mata pelajaran di rapor.
- **Rencana Detail:** Tersedia di [rencana_nomor_urut_mapel.md](file:///d:/xampp/htdocs/erapoteasy/docs/rencana_nomor_urut_mapel.md).
- **Langkah Utama:**
  - Penambahan kolom `nomor_urut` di tabel `mata_pelajaran`.
  - Update UI Admin Mata Pelajaran untuk input/edit nomor urut.
  - Update logika pengurutan (`usort`) pada controller cetak rapor.

