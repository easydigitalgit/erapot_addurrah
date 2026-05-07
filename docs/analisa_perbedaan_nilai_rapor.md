# Laporan Analisis: Perbedaan Nilai Rapor antara Wali Kelas dan Guru Mapel

## Deskripsi Masalah
Terdapat laporan bahwa nilai rapor yang muncul di dashboard Wali Kelas (saat preview/cetak rapor) berbeda dengan nilai yang terlihat pada dashboard atau inputan Guru Bidang Studi (khususnya Matematika).

## Hasil Investigasi
Berdasarkan audit kode pada backend (Controllers) dan alur data aplikasi, berikut adalah temuan kami:

### 1. Perbedaan Sumber Data (Source of Truth)
Aplikasi menggunakan dua cara berbeda dalam menampilkan nilai:
- **Dashboard Guru Mapel (`DashboardController`):** Menampilkan nilai secara **Real-Time (Live Calculation)**. Sistem menghitung rata-rata secara langsung dari tabel mentah `nilai_formatif` dan `nilai_sumatif` setiap kali halaman dibuka.
- **Preview Rapor Wali Kelas (`PreviewRaporController`):** Menampilkan nilai dari tabel **`nilai_rapor`**. Tabel ini berfungsi sebagai "Snapshot" atau hasil akhir yang sudah diproses.

### 2. Mekanisme Sinkronisasi Manual
Nilai dari Guru Mapel **tidak otomatis** masuk ke tabel `nilai_rapor`. Guru Mapel harus masuk ke menu **"Sinkronisasi Nilai Rapor"** dan menekan tombol **"Sinkronisasi/Simpan"** untuk mengirimkan hasil kalkulasi terbaru ke Wali Kelas.

Jika Guru Mapel mengubah nilai harian atau ujian tetapi **belum melakukan sinkronisasi**, maka:
- Guru Mapel akan melihat nilai BARU di dashboardnya.
- Wali Kelas akan tetap melihat nilai LAMA (atau kosong) di rapor siswa.

### 3. Pengaruh Perubahan Bobot Nilai
Sistem memiliki pengaturan bobot nilai (contoh: Formatif 30%, Sumatif 70%) di tabel `setting_bobot_nilai`. 
- Jika Admin mengubah bobot ini, kalkulasi di dashboard Guru Mapel akan berubah secara otomatis.
- Namun, nilai di tabel `nilai_rapor` (yang dilihat Wali Kelas) **tidak akan berubah** sampai Guru Mapel melakukan sinkronisasi ulang.

### 4. Perbedaan Kategori Penilaian
Discrepancy juga bisa terjadi jika terdapat ketidaksesuaian pemilihan kategori antara Guru dan Wali Kelas:
- Guru melakukan sinkronisasi untuk kategori **"Tengah Semester"**.
- Wali Kelas melakukan cetak rapor untuk kategori **"Akhir Semester"**.
Karena keduanya disimpan sebagai record yang berbeda di database, nilai yang muncul tentu akan berbeda.

## Kesimpulan
Masalah ini **sangat mungkin terjadi** dan merupakan konsekuensi dari desain sistem yang menggunakan tabel snapshot (`nilai_rapor`) untuk keperluan cetak rapor agar performa sistem tetap terjaga saat pencetakan massal.

**Penyebab paling utama kemungkinan besar adalah:**
Guru Bidang Studi (Matematika) sudah mengisi/mengubah nilai, tetapi **belum melakukan "Sinkronisasi Nilai Rapor"** untuk kelas tersebut, sehingga data yang sampai ke Wali Kelas adalah data lama.

## Rekomendasi Tindakan (Tanpa Mengubah Kode)
1. **Verifikasi ke Guru Mapel:** Pastikan Guru Mapel Matematika sudah membuka menu "Sinkronisasi Nilai Rapor", memilih kelas & kategori yang sesuai, dan menekan tombol sinkronisasi.
2. **Cek Pengaturan Bobot:** Pastikan Admin tidak mengubah pengaturan bobot nilai di tengah periode penilaian tanpa meminta semua guru untuk melakukan sinkronisasi ulang.
3. **Edukasi User:** Berikan instruksi kepada Guru Mapel bahwa setiap ada perubahan nilai, mereka WAJIB melakukan sinkronisasi agar nilai tersebut "terkirim" ke rapor Wali Kelas.
