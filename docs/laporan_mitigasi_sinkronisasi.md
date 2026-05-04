# Laporan Mitigasi: Ketidaksamaan Daftar Siswa Dashboard Admin & Wali Kelas

## 1. Deskripsi Masalah
Ditemukan adanya perbedaan data antara dashboard **Admin (Tingkat Rombel)** dan **Wali Kelas** terkait penempatan siswa setelah dilakukan pemindahan kelas (misal: dari kelas Intan ke Kyanite).
- **Admin**: Melihat siswa sudah berada di kelas baru (Kyanite).
- **Wali Kelas**: Tidak melihat siswa tersebut di daftar anggota kelasnya.

## 2. Analisis Akar Masalah (Root Cause)
Setelah dilakukan penelusuran kode, ditemukan bahwa aplikasi menggunakan dua sumber data yang berbeda untuk menampilkan daftar siswa:

1. **Dashboard Admin (Tingkat Rombel)**: Menggunakan kolom `rombel_id` langsung dari tabel `siswa`.
2. **Dashboard Wali Kelas**: Menggunakan tabel `anggota_rombel` (fitur "Mesin Waktu") yang mencatat sejarah penempatan siswa berdasarkan Tahun Ajaran dan Semester.

Masalah terjadi karena beberapa proses pemindahan siswa di dashboard Admin **hanya memperbarui tabel `siswa`** tetapi **lupa memperbarui tabel `anggota_rombel`**.

### Titik Lemah (Vulnerabilities):
- **Fitur Edit Siswa**: Saat Admin mengedit profil siswa dan mengubah kelasnya, sistem hanya memperbarui `siswa.rombel_id`.
- **Fitur Import Excel**: Proses import data siswa secara massal juga hanya memperbarui `siswa.rombel_id`.
- **Fitur Pindahkan Siswa (Tingkat Rombel)**: Meskipun sudah ada logika sinkronisasi, namun bergantung pada status "Aktif" di tabel `tahun_ajaran`. Jika status ini tidak tepat di server live, sinkronisasi gagal.

## 3. Mitigasi & Rekomendasi
Untuk memastikan data selalu sinkron, perlu dilakukan langkah-langkah berikut:

1. **Sinkronisasi Otomatis**: Menambahkan logika `Upsert` (Update or Insert) ke tabel `anggota_rombel` setiap kali kolom `rombel_id` pada tabel `siswa` berubah (baik lewat form Edit maupun Import).
2. **Audit Data Live**: Melakukan pengecekan manual pada tabel `anggota_rombel` di server live untuk siswa bernama **AYLA SHAUFA AZZAHRO** guna memastikan baris datanya sudah mengarah ke `rombel_id` kelas Kyanite untuk semester yang sedang berjalan.
3. **Penyamaan Logika**: Mengarahkan dashboard Admin untuk juga memvalidasi data berdasarkan `anggota_rombel` agar jika terjadi ketidaksinkronan, Admin dapat langsung mengetahuinya.

## 4. Rencana Tindakan
Saya telah menyusun rencana perbaikan teknis untuk memperbaiki celah sinkronisasi ini di `SiswaController` dan `TingkatRombelController` agar kejadian serupa tidak terulang di masa depan.

---
**Status Local**: Berhasil (Data sinkron karena proses pemindahan menggunakan fitur yang sudah memiliki logika sinkronisasi).
**Status Live**: Setengah Berhasil (Diduga pemindahan dilakukan melalui jalur yang belum memiliki logika sinkronisasi otomatis).
