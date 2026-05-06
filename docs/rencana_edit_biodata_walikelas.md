# Rencana Teknis: Fitur Edit Biodata Siswa oleh Wali Kelas

Dokumen ini menjelaskan rencana teknis untuk mengimplementasikan fitur pengeditan biodata siswa oleh Wali Kelas, yang sebelumnya hanya tersedia untuk Admin. Fitur ini akan dibatasi secara ketat sehingga Wali Kelas hanya dapat mengedit siswa yang berada di bawah perwaliannya pada tahun ajaran aktif.

## 1. Arsitektur Keamanan & Validasi

### Keamanan Berbasis Kepemilikan (Ownership Validation)
Setiap permintaan pengeditan akan divalidasi di sisi server (backend) untuk memastikan:
1. Pengguna memiliki peran `wali_kelas`.
2. `siswa_id` yang diminta benar-benar terdaftar di `rombel_id` yang dikelola oleh Wali Kelas tersebut pada tahun ajaran aktif.
3. Menggunakan join antara `siswa`, `anggota_rombel`, dan `rombel` untuk memverifikasi hubungan tersebut.

### Validasi Data
Mengikuti standar validasi Admin:
- **Uniqueness**: NIS, NISN, dan NIK harus unik (kecuali untuk record siswa itu sendiri saat update).
- **Format**: Validasi format email, nomor HP, dan tanggal lahir.
- **Ekskul**: Validasi agar siswa tidak memilih ekstrakurikuler yang sama lebih dari satu kali.

---

## 2. Perubahan Backend (API)

### Rute Baru (`app/Config/Routes.php`)
Menambahkan rute di dalam grup `wali`:
- `GET wali/daftar-siswa/get-detail/(:num)`: Mengambil data lengkap siswa & orang tua.
- `POST wali/daftar-siswa/update/(:num)`: Memproses pembaruan data.

### Controller (`app/Controllers/WaliKelas/DaftarSiswaController.php`)
- **`getDetail($id)`**: 
  - Validasi kepemilikan siswa.
  - Jika valid, return JSON berisi data dari tabel `siswa`, `orangtua_wali`, dan `users` (untuk foto).
- **`update($id)`**:
  - Validasi kepemilikan siswa (Cegah bypass via ID lain).
  - Proses upload foto (konversi WebP).
  - Sinkronisasi data ke tabel `siswa`.
  - Sinkronisasi data ke tabel `orangtua_wali`.
  - Sinkronisasi data ke tabel `users` (username & foto).
  - Sinkronisasi "Mesin Waktu" (`anggota_rombel`) jika ada perubahan rombel (meskipun biasanya Wali Kelas tidak mengubah rombel, fitur ini tetap disiapkan mengikuti logika Admin).

---

## 3. Perubahan Frontend (UI/UX)

### View (`app/Views/WaliKelas/daftar-siswa.php`)
- Menambahkan tombol "Edit Biodata" pada `profileModal`.
- Menambahkan `editStudentModal` yang berisi form lengkap (Bio, Alamat, Orang Tua, Foto, Ekskul).
- Form ini akan menggunakan desain yang sama dengan panel Admin untuk menjaga konsistensi.

### Asset JS (`public/assets/js/WaliKelas/daftar-siswa.js`)
- **`openEditModal(id)`**: Memanggil API `get-detail`, mengisi form modal.
- **`handleEditSubmit(event)`**: Mengirim data via AJAX menggunakan `FormData` (untuk mendukung upload file).
- **Validasi Client-side**: Memastikan input wajib terisi sebelum dikirim ke server.

---

## 4. Rencana Verifikasi

1. **Uji Coba Positif**: Mengedit biodata siswa sendiri dan memastikan data tersimpan di database.
2. **Uji Coba Negatif**: Mencoba menembak API update dengan `id` siswa dari kelas lain menggunakan tool seperti Postman/Insomnia; server harus mengembalikan error 403/Forbidden.
3. **Uji Coba Foto**: Memastikan upload foto berhasil terkompresi menjadi WebP dan muncul di profil.
4. **Uji Coba Duplikasi**: Memasukkan NISN yang sudah ada di siswa lain dan memastikan sistem menolak.
