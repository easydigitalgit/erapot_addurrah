# Prosedur Pencabutan Akses Edit Biodata (Wali Kelas)

Dibuat pada: 2026-05-06
Status: **Sudah Dilaksanakan**

## 1. Pendahuluan
Fitur edit biodata siswa kini tersedia bagi peran **Wali Kelas** untuk mempermudah pembaruan data tanpa harus masuk sebagai Admin. Namun, akses ini tetap dikendalikan secara ketat melalui sistem **RBAC (Role Based Access Control)**.

Dokumen ini menjelaskan cara mengelola akses tersebut, baik melalui pengaturan izin (*Permission*) maupun pencabutan peran (*Role*) ganda pada pengguna.

## 2. Mengelola Izin Edit (via Hak Akses)
Jika Anda ingin menonaktifkan fitur edit bagi **seluruh** Wali Kelas tanpa menghapus peran mereka:

1.  Login sebagai **Admin**.
2.  Buka menu **Pengaturan** > **Hak Akses**.
3.  Pilih Role **Wali Kelas**.
4.  Cari Modul **wali_kelas**.
5.  Hilangkan centang pada kolom **Update**.
6.  Klik **Simpan Perubahan**.

## 3. Keamanan Sistem (RBAC & Routes)
Fitur ini telah diintegrasikan dengan sistem keamanan aplikasi:
- **Routes Aktif**: Endpoint API di `Config/Routes.php` tetap aktif untuk mendukung operasional Wali Kelas.
- **Validasi Server**: Setiap permintaan `update` divalidasi oleh server menggunakan fungsi `has_permission()`. Jika izin dicabut di dashboard Admin (Langkah 2), maka akses API otomatis tertutup meskipun tombol masih terlihat.
- **UI Kondisional**: Tombol "Edit" di `Views/WaliKelas/daftar-siswa.php` hanya akan muncul jika izin `update` diberikan kepada role `wali_kelas`.

## 4. Cara Mencabut Role "Admin" (Menghilangkan Pilihan saat Login)

Jika seorang Wali Kelas memiliki peran ganda (seperti pada screenshot yang Anda lampirkan: Admin, Guru Mapel, Wali Kelas, dll), Anda dapat mencabut peran "Admin Sekolah" agar tidak lagi muncul sebagai pilihan saat ia login.

### Langkah-langkah:
1.  **Login sebagai Admin Utama (Super Admin).**
2.  Buka menu **Manajemen Pengguna** (biasanya ada di Sidebar atau menu Pengaturan).
3.  **Cari User Terkait**: Gunakan fitur pencarian (Search) dan masukkan nama atau email user tersebut (misal: `infopaerax@gmail.com`).
4.  **Kelola Hak Akses**:
    *   Cari kolom **"Role / Akses"** pada tabel pengguna.
    *   Klik tombol dropdown atau tombol akses (yang menampilkan angka jumlah akses, misal: "4 Akses").
    *   Akan muncul daftar checkbox semua role yang tersedia.
5.  **Uncheck "Admin Sekolah"**:
    *   Hilangkan tanda centang (uncheck) pada pilihan **Admin Sekolah**.
    *   Sistem akan otomatis menyimpan perubahan tersebut.
6.  **Verifikasi**:
    *   Minta user tersebut untuk **Logout** dan **Login** kembali.
    *   Pada layar "Pilih Akses Masuk", pilihan **Admin Sekolah** seharusnya sudah tidak ada lagi.

> [!TIP]
> Jika setelah dicabut user tersebut hanya menyisakan **satu role saja** (misal: hanya Wali Kelas), maka layar pemilihan akses (seperti di screenshot) tidak akan muncul lagi. User akan langsung diarahkan ke dashboard rolenya tersebut.

---

## 6. Mengapa Pilihan Login Masih Ada?
Jika Anda sudah menghapus permission di menu "Hak Akses" (Bagian 2) tapi pilihan "Admin Sekolah" masih muncul saat login, itu karena user tersebut masih terasosiasi dengan role Admin di tabel pengguna. Gunakan **Langkah di Bagian 5** untuk menghapus asosiasi role tersebut sepenuhnya.

---

## 7. Analisis: Kenapa Role Admin Terdisable (Kasus Tika Yeardila)

Pada screenshot kasus **Tika Yeardila**, terlihat bahwa checkbox "Super Admin" tercentang namun berwarna abu-abu (*disabled*) sehingga tidak bisa diklik.

### Mengapa hal ini terjadi?
Ini adalah **fitur keamanan (Safety Mechanism)** sistem untuk mencegah akun kehilangan akses utamanya. Penjelasannya secara teknis:
- Setiap akun memiliki satu **Role Utama (Primary Role)** yang tersimpan di tabel `users`.
- Pada kasus Tika Yeardila, role **Super Admin** saat ini terdaftar sebagai **Role Utama**-nya.
- Sistem melarang penghapusan Role Utama melalui menu dropdown cepat agar akun tidak menjadi "Yatim Piatu" (tanpa role utama), yang bisa menyebabkan error sistem saat login.

### Solusi untuk Mencabutnya:
Anda tidak bisa langsung menghapusnya dari menu dropdown "Akses". Anda harus mengubah **Role Utama**-nya terlebih dahulu:

1.  Klik tombol **Edit** (Ikon Pensil) di baris paling kanan pada user Tika Yeardila.
2.  Akan muncul modal **"Edit Pengguna"**.
3.  Pada kolom **"Role Pengguna"**, ubah dari "Super Admin" menjadi role lain (misalnya: **"Wali Kelas"**).
4.  Klik **Simpan Perubahan**.
5.  Setelah Role Utamanya berubah menjadi Wali Kelas, sekarang Anda bisa membuka kembali menu dropdown "Akses / Role".
6.  Checkbox **Super Admin** sekarang sudah aktif (tidak lagi *disabled*) dan Anda bisa menghilangkannya (Uncheck).

---
*Dokumentasi ini dibuat sebagai referensi perubahan sistem per tanggal 06 Mei 2026.*
