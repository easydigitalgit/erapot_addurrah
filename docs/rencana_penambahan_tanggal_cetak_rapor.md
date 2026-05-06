# Rencana Penambahan Field Tanggal Cetak Rapor

## Deskripsi Tujuan
Menambahkan input interaktif "Tanggal Cetak Rapor" pada halaman Pratinjau dan Cetak Rapor (Wali Kelas) agar wali kelas bisa mengatur tanggal rapor secara seragam sebelum melakukan pencetakan. Tanggal yang dipilih akan diubah menjadi format Bahasa Indonesia (misal: "06 Mei 2026") dan ditampilkan di bagian tanda tangan Wali Kelas dan Kepala Sekolah pada dokumen PDF.

## Detail Perubahan (Proposed Changes)

### 1. View `preview-rapor.php` (Frontend)
Merubah layout pengaturan rapor dari grid 3 kolom menjadi 4 kolom untuk menambahkan komponen input kalender.

#### [MODIFY] `backend/app/Views/WaliKelas/preview-rapor.php`
- Pada baris `116`, ubah class grid kolom dari `<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">` menjadi `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">`.
- Tambahkan blok input bertipe `date` (kalender) dengan ID `tglRapor` yang tampilannya disesuaikan dengan tema UI Erapot (menggunakan border, background styling, dan icon).
- Hapus input hidden `tglRapor` yang ada di baris `160`.

### 2. Controller `PreviewRaporController.php` (Backend)
Mengelola perubahan data dan konversi format tanggal antara Frontend (`YYYY-MM-DD`) dan Output PDF (Format Indonesia seperti `d F Y`).

#### [MODIFY] `backend/app/Controllers/WaliKelas/PreviewRaporController.php`
- **Di dalam Method `index()`**: Konversi variabel yang dikirim ke view untuk `$data['tanggal_rapor']` agar menggunakan standar kalender HTML5 (`Y-m-d`). Jika di dalam tabel admin masih tersimpan string seperti "20 Juni 2026", maka buatkan regex/parser ringan untuk mengkonversi nilai tersebut ke format `Y-m-d`.
- **Di dalam Method `printPDF()`**: Tangkap query GET `tgl_rapor`. Ubah kembali format `YYYY-MM-DD` menjadi format lokalisasi Indonesia (misal "06 Mei 2026"). Ini akan di-pass kembali ke `$data['tanggal_rapor']` sehingga tulisan di kolom tanda tangan rapor tetap berformat surat resmi Indonesia, bukan format mesin.

## Open Questions
- Apakah format string di tabel master (Tahun Ajaran) untuk tanggal rapor sudah terjamin `Y-m-d`, ataukah ia berupa plain string yang diketik manual (seperti "10 Desember 2026")? *(Saya telah mengasumsikan tipe data input dapat berupa string manual, sehingga saya akan menyisipkan logic parsing agar aman)*.

## Verification Plan
1. Buka dashboard Wali Kelas > Cetak Rapor.
2. Pastikan field kalender "Tanggal Cetak Rapor" muncul di sebelah Kategori Rapor.
3. Ubah tanggal secara manual melalui popup kalender di browser.
4. Klik tombol Cetak PDF (Rapor Lengkap).
5. Validasi bahwa baris tanda tangan pada halaman akhir PDF menampilkan tanggal baru yang dipilih secara seragam, lengkap dengan bulan format Bahasa Indonesia.
