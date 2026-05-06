# Dokumentasi Implementasi Edit Biodata Siswa (Wali Kelas)

Dibuat pada: 2026-05-06

## 1. Latar Belakang
Memberikan hak akses kepada Wali Kelas untuk mengedit biodata siswa di kelas perwaliannya secara mandiri, mengurangi beban kerja Admin namun tetap menjaga keamanan data.

## 2. Perubahan Database (RBAC)
Mengikuti aturan sistem perizinan aplikasi, kami menambahkan entri baru pada tabel `role_permissions` untuk role **Wali Kelas (Role ID: 3)**.

- **Modul**: `wali_kelas`
- **Permissions**: 
    - `can_view`: 1
    - `can_create`: 1 (Digunakan untuk fitur Catatan)
    - `can_update`: 1 (Digunakan untuk fitur Edit Biodata)
    - `can_delete`: 1
    - `can_special`: 1

*Catatan: Kami menggunakan Seeder `WaliKelasPermissionSeeder` untuk melakukan perubahan ini agar sesuai dengan struktur CodeIgniter.*

## 3. Perubahan Backend (PHP/CodeIgniter)

### Routes.php
Menambahkan endpoint baru di grup `wali`:
- `GET /wali/daftar-siswa/get-detail/(:any)` -> Mengambil data lengkap siswa (termasuk orang tua).
- `POST /wali/daftar-siswa/update` -> Memproses pembaruan data.

### DaftarSiswaController.php
- **`getDetail($id)`**: Mengambil data dari tabel `siswa` dan `orangtua_wali`. Dilengkapi validasi kepemilikan (Wali Kelas hanya bisa mengambil data siswanya sendiri).
- **`update()`**: Melakukan validasi input (termasuk duplikasi NIS/NISN/NIK) dan memperbarui database. Dilengkapi logika penanganan upload foto (konversi ke WebP).
- **`index()`**: Memulihkan logika *batch query* untuk menghitung `rata_nilai`, `persen_absen`, dan progres `tahfidz`.

## 4. Perubahan Frontend (HTML/JS)

### Views/WaliKelas/daftar-siswa.php
- Menambahkan **Modal Form** yang komprehensif (Identitas, Fisik, Keluarga, Akademik, Orang Tua).
- Menambahkan tombol **Edit (Emerald Pencil)** di kolom Aksi.
- Mengubah icon **Catatan (Chat Bubble)** untuk membedakannya dengan tombol Edit agar tidak membingungkan.
- Mengaktifkan kembali pengecekan `has_permission('wali_kelas', 'update')`.

### public/assets/js/WaliKelas/daftar-siswa.js
- **`openEditModal(id, btn)`**: Mengambil data via AJAX, mengisi form modal, dan menangani indikator *loading* pada tombol.
- **`submitEditStudent(event)`**: Menangani pengiriman form via FormData (mendukung upload foto) dan menampilkan SweetAlert2.

## 5. Keamanan
- **Ownership Validation**: Setiap permintaan Edit/Update diverifikasi terhadap `anggota_rombel` aktif untuk memastikan Wali Kelas tidak dapat mengedit siswa dari kelas lain.
- **CSRF Protection**: Terintegrasi dengan sistem keamanan CodeIgniter.

---
*Fitur ini sekarang siap digunakan dan selaras dengan standar Admin.*
