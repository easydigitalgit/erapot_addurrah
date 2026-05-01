# Analisa Keamanan Data Siswa (Validasi NIS, NIK, NISN)

Berdasarkan audit kode pada `SiswaController.php`, ditemukan bahwa sistem saat ini **sangat longgar** terhadap duplikasi data, bahkan secara sengaja mengizinkan duplikasi NIS pada proses import.

## Temuan Analisa

### 1. Proses Tambah & Edit Siswa (`store` & `update`)
*   **NISN & NIK**: Sudah ada validasi dasar yang mencegah duplikasi. Jika data sudah ada, sistem akan mengembalikan pesan error.
*   **NIS**: **TIDAK ADA VALIDASI**. Sistem mengandalkan generate otomatis, namun jika user mengubahnya secara manual atau terjadi race condition, sistem akan menerima NIS duplikat tanpa peringatan.

### 2. Proses Import Dapodik/Excel (`import`)
*   **Penghapusan Index**: Sistem secara sengaja menjalankan perintah `ALTER TABLE siswa DROP INDEX nis` (Garis 764). Ini adalah praktik berbahaya karena menghilangkan proteksi tingkat database terhadap data ganda.
*   **Pernyataan Sistem**: Pesan sukses import secara eksplisit menyatakan `"(NIS diizinkan kembar)"` (Garis 1056).
*   **Logika Matching yang Lemah**: Sistem mencoba mencocokkan siswa berdasarkan NISN -> NIK -> NIS+Nama -> Nama. Jika tidak ditemukan, sistem akan membuat record baru. Ini sangat rawan menghasilkan data ganda jika salah satu identitas (misal NISN) berbeda sedikit saja.
*   **Validasi "Soft"**: Jika NISN atau Email ditemukan ganda saat import, sistem tidak menolak baris tersebut, melainkan hanya mengosongkan (`null`) kolom tersebut pada record baru (Garis 977-980).

---

## Rencana Perbaikan (Implementation Plan)

### Tahap 1: Proteksi Database
1.  **Restorasi Unique Index**: Menghapus baris kode yang melakukan `DROP INDEX nis`.
2.  **Unique Constraint**: Menambahkan (atau memastikan adanya) UNIQUE constraint pada kolom `nis`, `nisn`, dan `nik` di tabel `siswa`. Ini adalah baris pertahanan terakhir.

### Tahap 2: Perbaikan Logic Backend (`SiswaController.php`)
1.  **Validasi Ketat di `store` & `update`**:
    *   Menambahkan pengecekan `where('nis', $nis)->countAllResults()` sebelum proses simpan.
2.  **Refactoring Logic `import`**:
    *   Mengganti logika matching agar lebih strict.
    *   Jika ditemukan NIS/NISN/NIK yang sudah ada di baris Excel, sistem harus:
        *   **Opsi A**: Melewati (skip) baris tersebut dan mencatatnya sebagai error/warning.
        *   **Opsi B**: Melakukan update data (jika diizinkan) namun tetap menjaga integritas ID.
    *   Menghilangkan fitur "mengizinkan NIS kembar".

### Tahap 3: User Interface (Frontend)
1.  **Real-time Validation**: Menambahkan event listener pada input NIS, NISN, dan NIK di form tambah/edit untuk mengecek ketersediaan data ke server (AJAX) sebelum form disubmit.
2.  **Import Preview**: Menambahkan tahap "Preview" sebelum import benar-benar dilakukan, yang akan menampilkan baris mana saja yang bermasalah (duplikat).

---
## Update 30 April 2026: Relaksasi Validasi No. HP Orang Tua

### Masalah
Satu orang tua seringkali memiliki lebih dari satu anak (kakak-beradik) di sekolah yang sama. Sistem saat ini cenderung menimpa record di `orangtua_wali` jika nomor HP yang sama digunakan kembali, karena sistem mencoba mencari record berdasarkan `user_id` (akun login).

### Rencana Perubahan
1.  **Shared Account**: Mengizinkan beberapa siswa berbagi satu akun orang tua (`user_id` yang sama di tabel `users`).
2.  **Unique Records per Student**: Setiap siswa tetap memiliki record unik di tabel `orangtua_wali` (berdasarkan `siswa_id`), meskipun `user_id` dan `no_hp_ortu` sama dengan siswa lain.
3.  **Removal of Uniqueness Checks**: Menghapus validasi `email_ortu` unik di level backend controller yang menghambat proses penyimpanan jika email sudah terdaftar.

### Implementasi pada `SiswaController.php`
-   Ubah `store()`: Selalu gunakan `insert` untuk `orangtua_wali`.
-   Ubah `update()`: Pastikan `user_id` diperbarui jika nomor HP ortu diubah ke nomor yang sudah terdaftar di sistem.
