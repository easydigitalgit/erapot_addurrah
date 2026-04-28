# Analisis Import CSV Menu Absensi Kelas

## Ringkasan masalah

Pada menu `http://localhost:8080/wali/absensi-kelas`, alur yang terjadi saat ini adalah:

1. User mengunduh template absensi dari tombol `downloadTemplate()`.
2. Template dihasilkan sebagai file `.xls` berbasis HTML, bukan file CSV murni.
3. File dibuka di Excel lalu disimpan ulang sebagai CSV.
4. Saat proses `Save As`, Excel mengubah header tanggal dari format `YYYY-MM-DD` menjadi format lokal seperti `DD/MM/YYYY`.
5. Backend import hanya menerima header tanggal dengan regex `YYYY-MM-DD`, sehingga upload gagal dengan pesan:
   `Format kolom tanggal YYYY-MM-DD tidak ditemukan.`

## Bukti yang ditemukan

### 1. Backend import hanya menerima tanggal `YYYY-MM-DD`

Di [AbsensiKelasController.php](/d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/AbsensiKelasController.php:226), fungsi `importAbsensi()` memindai baris CSV dan hanya menganggap sebuah kolom sebagai kolom tanggal jika cocok dengan regex berikut:

```php
/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/
```

Referensi penting:

- [AbsensiKelasController.php](/d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/AbsensiKelasController.php:255)
- [AbsensiKelasController.php](/d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/AbsensiKelasController.php:264)

Artinya, format seperti `01/04/2026` akan selalu ditolak.

### 2. Template yang diunduh bukan CSV, tetapi `.xls`

Di [absensi-kelas.js](/d:/xampp/htdocs/erapoteasy/backend/public/assets/js/WaliKelas/absensi-kelas.js:977), fungsi `downloadTemplate()` membuat file dengan:

- MIME: `application/vnd.ms-excel`
- ekstensi file: `.xls`

Template berasal dari `createExcelHTML(true)` di [absensi-kelas.js](/d:/xampp/htdocs/erapoteasy/backend/public/assets/js/WaliKelas/absensi-kelas.js:849).

Jadi, user memang diarahkan untuk:

1. download `.xls`
2. isi di Excel
3. convert lagi ke `.csv`

Masalahnya, langkah konversi ini mengubah format header tanggal.

### 3. File CSV hasil konversi memang sudah berubah format tanggalnya

Di file contoh [absensi_kelas_biduri_april_2026.csv](/d:/xampp/htdocs/erapoteasy/docs/absensi_kelas_biduri_april_2026.csv:14), header tanggal terbaca seperti:

```text
NISN;Nama Siswa;01/04/2026;02/04/2026;03/04/2026;...
```

Bukan:

```text
NISN;Nama Siswa;2026-04-01;2026-04-02;2026-04-03;...
```

Ini menjelaskan langsung kenapa importer tidak menemukan format `YYYY-MM-DD`.

## Akar masalah

Akar masalahnya bukan pada file CSV user semata, tetapi pada ketidakcocokan antara:

- format template yang dihasilkan frontend
- perilaku Excel saat menyimpan ulang ke CSV
- validasi backend yang terlalu kaku

Dengan kata lain, template resmi aplikasi menghasilkan alur yang secara natural memicu error di importer.

## Yang perlu diperbaiki

### Prioritas 1: longgarkan parser tanggal di backend

Importer sebaiknya tidak hanya menerima `YYYY-MM-DD`, tetapi juga format yang realistis muncul dari Excel, minimal:

- `YYYY-MM-DD`
- `DD/MM/YYYY`
- `D/M/YYYY`

Lebih baik lagi jika setiap header tanggal dinormalisasi dulu ke format `Y-m-d`, baru dipakai untuk proses import.

Contoh pendekatan:

1. baca nama kolom
2. trim dan bersihkan karakter tersembunyi
3. coba parse dengan beberapa pola tanggal
4. jika valid, simpan hasil normalisasi sebagai `Y-m-d`

Ini adalah perbaikan paling penting karena langsung menyelesaikan error yang user alami sekarang.

### Prioritas 2: ubah generator template agar tidak memancing auto-format Excel

Saat ini template tanggal ditulis sebagai string seperti `2026-04-01`, tetapi karena file dibuka sebagai Excel `.xls`, Excel tetap bisa menganggapnya sebagai tanggal dan saat ekspor CSV mengubah tampilannya ke format lokal.

Pilihan perbaikan:

1. Sediakan tombol download template CSV langsung, bukan `.xls`.
2. Jika tetap ingin `.xls`, buat proses import menerima hasil konversi Excel yang umum.
3. Pertimbangkan dukungan upload `.xlsx` langsung lalu konversi di server/client, seperti modul lain di project ini.

Catatan: hanya mengandalkan instruksi "Save As CSV" belum cukup, karena problem utamanya ada pada transformasi format tanggal oleh Excel.

### Prioritas 3: perbaiki pesan error agar lebih membantu

Pesan sekarang:

```text
Format kolom tanggal YYYY-MM-DD tidak ditemukan.
```

Pesan ini benar secara teknis, tetapi kurang membantu user. Sebaiknya diganti menjadi sesuatu seperti:

```text
Kolom tanggal tidak dikenali. Gunakan template asli atau simpan CSV dengan format tanggal YYYY-MM-DD / DD/MM/YYYY.
```

Kalau parser backend sudah diperluas, error ini akan jauh lebih jarang muncul.

### Prioritas 4: pertimbangkan header row yang lebih stabil

Saat ini importer mencari tanggal dengan memindai semua baris sampai menemukan pola tanggal. Ini cukup fleksibel, tetapi tetap rapuh karena bergantung pada tampilan teks header.

Opsi yang lebih stabil:

1. tetapkan satu baris header data yang pasti
2. atau tambahkan marker tetap
   contoh: `NISN,Nama Siswa,DATE:2026-04-01,...`

Lalu backend membaca kolom dengan marker yang tidak akan diubah Excel.

## Rekomendasi implementasi

Urutan perbaikan yang paling aman:

1. Perbaiki `importAbsensi()` agar bisa membaca `DD/MM/YYYY` dan menormalisasi ke `Y-m-d`.
2. Setelah itu, evaluasi ulang mekanisme download template:
   lebih ideal bila template bisa diunduh langsung sebagai CSV yang siap upload.
3. Perbarui pesan bantuan di UI upload agar user tahu format yang didukung.

## Kesimpulan

Error ini terjadi karena alur resmi aplikasi saat ini menghasilkan file `.xls` yang ketika dikonversi ke CSV oleh Excel mengubah header tanggal menjadi `DD/MM/YYYY`, sedangkan backend hanya menerima `YYYY-MM-DD`.

Perbaikan utamanya ada di backend importer, bukan di user. User sudah mengikuti alur yang secara wajar disediakan aplikasi, tetapi aplikasi belum cukup toleran terhadap format hasil konversi Excel.
