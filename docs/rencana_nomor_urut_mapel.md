# Rencana Implementasi Penambahan Nomor Urut Mata Pelajaran

Fitur ini bertujuan untuk memungkinkan Admin mengatur urutan mata pelajaran yang tampil pada rapor melalui kolom `nomor_urut` di menu Mata Pelajaran. Urutan ini kemudian akan diterapkan secara otomatis pada saat pencetakan rapor (Preview & Cetak PDF).

## Perubahan Database

- Menambahkan kolom `nomor_urut` pada tabel `mata_pelajaran`.
- **Query SQL:** `ALTER TABLE mata_pelajaran ADD COLUMN nomor_urut INT DEFAULT 0 AFTER id;`

## Komponen Backend

### 1. Model
- **`App\Models\Admin\MataPelajaranModel.php`**
  - Menambahkan `'nomor_urut'` ke dalam properti `$allowedFields`.

### 2. Controllers
- **`App\Controllers\Admin\MataPelajaranController.php`**
  - `index()`: Mengirimkan data `nomor_urut` ke view.
  - `store()`: Mengambil dan menyimpan `nomor_urut` dari request POST.
  - `update()`: Mengambil dan memperbarui `nomor_urut` dari request POST.
  - `downloadTemplate()`: Menambahkan kolom "Nomor Urut" pada template Excel.
  - `import()`: Membaca kolom "Nomor Urut" dari file Excel yang diunggah.

- **`App\Controllers\WaliKelas\PreviewRaporController.php`**
  - Memastikan query join ke `mata_pelajaran` menyertakan kolom `nomor_urut`.
  - Mengubah logika `usort` agar mengurutkan berdasarkan `nomor_urut` terlebih dahulu (ASC), kemudian `nama_mapel`.

- **`App\Controllers\Admin\CetakRaporController.php`**
  - Melakukan penyesuaian yang sama dengan `PreviewRaporController.php` (Query & sorting logic).

### 3. Bahasa (Localization)
- **`App\Language\id\Admin\MataPelajaran.php`**
  - Menambahkan label `'th_no_urut' => 'No. Urut'` dan `'lbl_no_urut' => 'Nomor Urut Rapor'`.

## Komponen Frontend

### 1. View
- **`backend/app/Views/admin/mata-pelajaran.php`**
  - Menambahkan kolom "No. Urut" pada tabel utama.
  - Menambahkan input field "Nomor Urut" pada Modal Tambah Mapel.
  - Menambahkan input field "Nomor Urut" pada Modal Edit Mapel.

### 2. Assets (JavaScript)
- **`backend/public/assets/js/Admin/mata-pelajaran.js`**
  - Memperbarui fungsi `populateMapel()` untuk menampilkan kolom nomor urut.
  - Memperbarui fungsi `showEditMapelModal()` untuk memuat data nomor urut ke dalam form edit.

## Verifikasi Plan

### Manual Verification
- Menjalankan migrasi manual (SQL ALTER TABLE).
- Menginput nomor urut pada beberapa mata pelajaran di menu Admin.
- Membuka Preview Rapor (Wali Kelas) dan Cetak Rapor (Admin) untuk memastikan urutan mata pelajaran sudah sesuai dengan nomor urut yang diatur.
- Mencoba fitur Import Excel dengan kolom nomor urut baru.

## Kompatibilitas Query
- Penambahan kolom baru tidak akan merusak query yang sudah ada selama query tersebut menggunakan `SELECT *` atau join standar.
- Namun, beberapa query yang spesifik mendefinisikan kolom pada join perlu diperbarui secara eksplisit (seperti di PreviewRaporController).
