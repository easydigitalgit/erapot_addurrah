# Rencana Implementasi Fitur Tanggal Cetak Rapor Tahfizh

Dokumen ini memuat rencana teknis (blueprint) untuk menambahkan fitur pemilihan "Tanggal Cetak Rapor" pada modul Manajemen Rapor Tahfizh, baik di dashboard Admin maupun Wali Kelas. Tujuannya adalah agar tanggal yang tercetak pada halaman rapor tahfizh bisa disesuaikan secara fleksibel (tidak selalu tanggal hari ini), sehingga seragam sesuai dengan kebijakan administrasi sekolah.

## 1. Analisis Kebutuhan
Saat ini, tanggal rapor tahfizh di-hardcode ke tanggal sistem hari ini (`date('d F Y')`) di dalam fungsi `cetakRapor` (Controller).
Kita perlu menambahkan input *Date Picker* di halaman daftar siswa tahfizh agar pengguna dapat mengatur tanggal yang diinginkan sebelum mengklik tombol "Cetak".

## 2. Rencana Perubahan: Dashboard Admin

### A. Tampilan (View)
**File Target**: `backend/app/Views/admin/tahfidz/index.php`
- **Modifikasi Layout Filter**:
  Ubah grid filter dari `grid-cols-1 md:grid-cols-3` menjadi `grid-cols-1 md:grid-cols-2 lg:grid-cols-4`.
- **Penambahan Elemen**:
  Tambahkan sebuah input bertipe `date` (misalnya dengan id `filterTglRapor`). Value defaultnya diambil dari variabel `$tanggal_rapor` yang dikirim dari controller (format `YYYY-MM-DD`).
- **Pembaruan Logika JS**:
  Pada fungsi `renderTable()` saat mencetak tombol cetak, ambil nilai dari input `filterTglRapor` dan sematkan ke dalam URL pemanggilan `openIframePreview` (misal: `&tgl_rapor=${tglRapor}`).

### B. Pemrosesan (Controller)
**File Target**: `backend/app/Controllers/Admin/TahfidzController.php`
- **Fungsi `index()`**:
  Cek tahun ajaran aktif, ambil field `tanggal_rapor` jika ada, lalu format menjadi `YYYY-MM-DD` dan kirim ke view sebagai `$this->data['tanggal_rapor']`.
- **Fungsi `cetakRapor()`**:
  Tangkap parameter `tgl_rapor` via method GET (`request()->getGet('tgl_rapor')`). Konversi format `YYYY-MM-DD` ini menggunakan mapping array nama bulan Indonesia (Januari, Februari, dsb.) menjadi format teks lokal (contoh: `06 Mei 2026`). Simpan hasil akhirnya ke kunci array `['tanggal_rapor']` pada parameter data yang dipassing ke mPDF.

## 3. Rencana Perubahan: Dashboard Wali Kelas

### A. Tampilan (View)
**File Target**: `backend/app/Views/WaliKelas/tahfidz/index.php`
- **Modifikasi Layout Filter**:
  Saat ini hanya ada dropdown "Juz" (ukuran `md:w-1/3`). Tambahkan elemen baru untuk input "Tanggal Cetak Rapor" di sebelahnya dengan gaya desain yang sama. Gunakan grid atau flex-row (`md:w-2/3` dibagi dua).
- **Penambahan Elemen**:
  Sama halnya dengan Admin, tambahkan input `date` ber-id `filterTglRapor` yang terisi nilai default dari sistem.
- **Pembaruan Logika JS**:
  Modifikasi `renderTable()` agar menyisipkan parameter `&tgl_rapor=...` pada tombol `openIframePreview`.

### B. Pemrosesan (Controller)
**File Target**: `backend/app/Controllers/WaliKelas/TahfidzController.php`
- **Fungsi `index()`**:
  Tambahkan logika untuk menyiapkan default date (`YYYY-MM-DD`) dan mengirimnya ke view (sama seperti admin).
- **Fungsi `cetakRapor()`**:
  Modifikasi pengambilan tanggal dengan memprioritaskan parameter GET `tgl_rapor`. Terapkan fungsi parsing bulan Indonesia untuk meng-override default date.

## 4. Keamanan & Reliabilitas
1. Jika pengguna tidak memilih tanggal (mengosongkan form), sistem harus memiliki fallback ke `date('Y-m-d')`.
2. Validasi dengan `preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglRaporRaw)` akan diaplikasikan di controller untuk mencegah error saat proses konversi format string tanggal.
3. Angka satuan (seperti tanggal 6) harus tetap menampilkan format dengan angka 0 di depan (contoh: `06 Mei 2026`).

---
**Status Dokumen**: Siap untuk diimplementasikan.
