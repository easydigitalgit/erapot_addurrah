# Dokumentasi Implementasi Aktif/Nonaktif Mata Pelajaran

Dibuat pada: 2026-05-31

## 1. Latar Belakang
Menambahkan fitur untuk mengaktifkan dan menonaktifkan mata pelajaran di dalam sistem. Mata pelajaran yang dinonaktifkan tidak akan ditampilkan di lembar Rapor PDF siswa (baik di sisi Admin, Wali Kelas, maupun Orang Tua), namun data pengurutannya (`nomor_urut`) harus tetap konsisten dan terjaga.

## 2. Perubahan Database
Tabel `mata_pelajaran` sudah memiliki kolom `status` bertipe `ENUM('Aktif', 'Non-aktif')` dengan default `'Aktif'`. Perubahan ini sepenuhnya memanfaatkan kolom tersebut untuk menyaring status keaktifan mata pelajaran.

## 3. Perubahan Backend (PHP/CodeIgniter)

### Routes.php
Menambahkan route baru di dalam grup `admin`:
- `POST /admin/mata-pelajaran/toggle-status/(:num)` -> Mengubah status mata pelajaran (Aktif <=> Non-aktif) secara langsung.

### MataPelajaranController.php
- **`toggleStatus($id)`**: Menambahkan method baru untuk mengubah status mata pelajaran. Mengambil status saat ini dari database dan membalik nilainya, lalu mengembalikan respon JSON sukses/gagal.
- **`store()`**: Memperbarui penanganan penyimpanan mata pelajaran baru agar menerima nilai input `status` (default `'Aktif'`).
- **`update($id)`**: Memperbarui penanganan pembaruan data mata pelajaran agar menerima nilai input `edit_status`.

### Cetak/Unduh Rapor Controllers
Agar mata pelajaran yang dinonaktifkan tidak tampil di rapor, kami memperbarui controller berikut:
- **`App\Controllers\WaliKelas\PreviewRaporController.php`** (Method `printPDF`)
- **`App\Controllers\Admin\CetakRaporController.php`** (Method `printPDF`)
- **`App\Controllers\OrangTua\AkademikController.php`** (Method `generateRaporPDF`)

Perubahan meliputi:
1. Memastikan kolom `m.status` terpilih di semua query penarikan mata pelajaran (`jadwal_pelajaran`, `guru_mapel`, dan `nilai_rapor`).
2. Menyaring (filter out) mata pelajaran yang memiliki status `'Non-aktif'` sebelum dipetakan dan dikirim ke view rapor.
3. Menjaga urutan tetap sesuai dengan mengurutkan mata pelajaran pasca-filter menggunakan fungsi `usort` berdasarkan `nomor_urut` lalu `nama_mapel`.

## 4. Perubahan Frontend (HTML/JS)

### Views/admin/mata-pelajaran.php
- Menambahkan input select dropdown **Status** di dalam `addMapelModal` (Modal Tambah Mapel).
- Menambahkan input select dropdown **Status** di dalam `editMapelModal` (Modal Edit Mapel).

### public/assets/js/Admin/mata-pelajaran.js
- Mengubah kolom **STATUS** pada tabel daftar mata pelajaran agar merender tombol/badge interaktif `<button onclick="toggleMapelStatus('${mapel.id}')">`. Hal ini memungkinkan admin untuk mengaktifkan/menonaktifkan mapel secara cepat langsung dari baris tabel.
- Menambahkan fungsi `toggleMapelStatus(id)` untuk melakukan request AJAX POST ke endpoint `toggle-status`.
- Memperbarui fungsi `showEditMapelModal(id)` agar mengisi/memilih nilai Status yang sesuai saat form modal edit dimuat (`setValue('edit_status', mapel.status || 'Aktif')`).

---
*Fitur ini sekarang telah diimplementasikan penuh dan terintegrasi di seluruh modul cetak rapor.*
