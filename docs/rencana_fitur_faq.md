# Rencana Pengembangan Fitur FAQ - eRapot Easy

Dokumen ini berisi rencana teknis dan fungsional untuk penambahan fitur FAQ (Frequently Asked Questions) pada aplikasi eRapot Easy. Fitur ini bertujuan untuk menyediakan pusat bantuan mandiri bagi seluruh level pengguna.

## 1. Tujuan Fitur
- Memberikan panduan penggunaan aplikasi secara mandiri.
- Mengurangi beban tanya-jawab administratif melalui tiket atau pesan manual.
- Menyediakan konten tutorial baik dalam bentuk teks maupun video (YouTube).

## 2. Struktur Data & Database

### A. Tabel `faq_categories`
Digunakan untuk mengelompokkan artikel agar mudah dicari.
- `id`: Primary Key (Auto Increment)
- `name`: Nama kategori (Contoh: "Panduan Penilaian", "Masalah Teknis")
- `slug`: URL-friendly name
- `icon`: Class icon (FontAwesome/Bootstrap Icons) untuk estetika UI
- `display_order`: Urutan tampilan
- `timestamps`: `created_at` & `updated_at`

### B. Tabel `faqs`
Penyimpanan utama konten artikel.
- `id`: Primary Key
- `category_id`: Foreign Key ke `faq_categories`
- `title`: Judul FAQ
- `slug`: URL-friendly title
- `content`: Isi artikel (format HTML/Text)
- `type`: Tipe konten (`text` atau `media`)
- `media_url`: Link YouTube (jika tipe adalah media)
- `audience`: Target pembaca (JSON/Array: `admin`, `guru`, `wali_kelas`, `orang_tua`, `siswa`)
- `is_published`: Status publikasi (0/1)
- `view_count`: Statistik jumlah dilihat (optional)
- `timestamps`: `created_at` & `updated_at`

## 3. Alur Kerja (Workflow)

### A. Sisi Administrator (Manajemen)
1. Admin masuk ke menu **Pusat Bantuan > Kelola FAQ**.
2. Admin dapat membuat kategori baru.
3. Saat membuat artikel FAQ, Admin menentukan:
   - Judul dan isi konten.
   - Kategori artikel.
   - **Target Audience**: Admin bisa mencentang siapa saja yang boleh melihat artikel ini (Contoh: Hanya Guru dan Wali Kelas).
   - **Tipe Media**: Jika artikel berupa tutorial video, Admin cukup memasukkan link YouTube.
4. Admin mempublikasikan artikel.

### B. Sisi Pengguna (User Interface)
1. Pengguna melihat menu **Pusat Bantuan** di sidebar.
2. Halaman utama menampilkan:
   - **Kolom Pencarian**: Mencari artikel berdasarkan judul atau kata kunci konten.
   - **Kategori**: Filter artikel berdasarkan kategori yang ada.
   - **Daftar Artikel**: Hanya menampilkan artikel yang sesuai dengan Role pengguna tersebut.
3. Saat artikel dibuka:
   - Jika tipe teks: Menampilkan artikel lengkap.
   - Jika tipe media: Menampilkan **YouTube Player** yang tersemat (embedded) sehingga video bisa diputar langsung di dalam aplikasi tanpa pindah ke tab baru.

## 4. Rincian Teknis Implementasi

### Backend (CodeIgniter 4)
- **Controller Admin**: `App\Controllers\Admin\FaqController` (CRUD standar).
- **Controller Public**: `App\Controllers\HelpController` (Menangani tampilan user dan filtering role).
- **Model**: `App\Models\FaqModel` dengan query cakupan (scope) untuk memfilter `audience` berdasarkan session `role_id` user.
- **YouTube Helper**: Fungsi untuk mengekstrak Video ID dari berbagai format link YouTube (long URL, short URL, mobile URL).

### Frontend (View & UI/UX)
- **UI Admin**: Menggunakan tabel data (DataTables) untuk list dan form input dengan editor Summernote.
- **UI User**: Desain modern menggunakan kartu (Cards) atau Accordion. Menggunakan AJAX untuk fitur pencarian agar responsif.
- **Media Player**: Menggunakan Iframe YouTube API yang dibungkus dalam container responsif (Bootstrap `ratio-16x9`).

## 5. Rencana Pengujian (Verification)
- **Akses Role**: Memastikan user dengan role 'Siswa' tidak bisa melihat artikel yang ditujukan hanya untuk 'Guru'.
- **Pencarian**: Menguji akurasi hasil pencarian dengan berbagai kata kunci.
- **Media**: Memastikan video YouTube dapat diputar dan layar menyesuaikan (responsive) saat dibuka di perangkat mobile.

---

**Status Rencana**: Menunggu Persetujuan Klien.
**Estimasi Waktu**: 3-5 Hari Kerja.
