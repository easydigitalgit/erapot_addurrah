# Catatan Implementasi: Fitur Ledger Nilai & Ledger Ekskul Wali Kelas

Dokumen ini mencatat ringkasan (summary) teknis hasil implementasi penambahan fitur **Ledger Nilai** dan **Ledger Ekskul** ke dalam Dashboard Wali Kelas.

---

## 1. Detail Rute Baru (`backend/app/Config/Routes.php`)

Daftar rute berikut telah ditambahkan di dalam grup rute `'wali'` yang diproteksi dengan filter peran `'role:wali_kelas'`:

```php
    // -- Ledger Nilai (Wali Kelas) --
    $routes->group('cetak-leger', function ($routes) {
        $routes->get('/', 'CetakLegerController::index');
        $routes->post('get-data', 'CetakLegerController::getData');
        $routes->get('export-excel', 'CetakLegerController::exportExcel');
    });

    // -- Ledger Ekskul (Wali Kelas) --
    $routes->group('cetak-leger-ekskul', function ($routes) {
        $routes->get('/', 'CetakLegerEkskulController::index');
        $routes->post('get-data', 'CetakLegerEkskulController::getData');
        $routes->get('export-excel', 'CetakLegerEkskulController::exportExcel');
    });
```

---

## 2. File & Komponen yang Ditambahkan/Dimodifikasi

### A. Controllers (Backend)
1. **`backend/app/Controllers/WaliKelas/CetakLegerController.php`**
   - Menangani pengambilan data rekapitulasi nilai akademik kelas perwalian yang login secara otomatis.
   - Menyediakan endpoint JSON `/get-data` dan download Excel `/export-excel`.
2. **`backend/app/Controllers/WaliKelas/CetakLegerEkskulController.php`**
   - Menangani rekapitulasi nilai dan keaktifan ekstrakurikuler kelas perwalian yang login.
   - Menyediakan endpoint JSON `/get-data` dan download Excel `/export-excel`.
3. **`backend/app/Controllers/WaliKelasBaseController.php` (Modified)**
   - Menambahkan menu **Ledger Nilai** dan **Ledger Ekstrakurikuler** ke dalam submenu **Rapor** di sidebar navigasi.

### B. Views (Frontend)
1. **`backend/app/Views/WaliKelas/cetak-leger.php`**
   - Halaman antarmuka untuk melihat, memfilter kategori (Tengah/Akhir Semester) & Tahun Ajaran, mengurutkan data (berdasarkan Abjad/Rank), serta menyembunyikan/menampilkan Nilai Angka dan Predikat Huruf.
   - Opsi Rombel dikunci (disabled) hanya untuk rombel Wali Kelas yang bersangkutan.
2. **`backend/app/Views/WaliKelas/cetak-leger-ekskul.php`**
   - Halaman antarmuka matriks nilai ekskul siswa per kelas.
   - Opsi Rombel dikunci (disabled) hanya untuk rombel Wali Kelas yang bersangkutan.

### C. Assets Javascript (Client-Side Logic)
1. **`backend/public/assets/js/WaliKelas/cetak-leger.js`**
   - Mengontrol visualisasi data, sinkronisasi filter via AJAX, rendering statistik kelas (Rata-rata Kelas, Nilai Tertinggi/Terendah, % Ketuntasan), dan fungsi cetak browser dengan sistem *automatic page-chunking* (15 baris per halaman) agar rapi saat diprint ke kertas Legal landscape.
2. **`backend/public/assets/js/WaliKelas/cetak-leger-ekskul.js`**
   - Mengontrol AJAX loading data matriks ekskul, rendering footer total peserta aktif per ekskul, dan fungsi cetak browser serta ekspor Excel.

---

## 3. Aspek Keamanan & Validasi (Class-Level Isolation)

Fitur ini didesain dengan tingkat keamanan tinggi untuk mencegah eksploitasi parameter tampering ( bypass ID rombel ):
- Di sisi server, parameter `rombel_id` yang dikirim dari browser diabaikan atau divalidasi silang. Server akan **selalu mengambil data rombel berdasarkan relasi `wali_kelas_id` dari guru yang login** di session untuk tahun ajaran terpilih.
- Jika Wali Kelas tidak memiliki kelas perwalian pada tahun ajaran/semester yang dipilih, server mengembalikan respon kosong secara aman dengan status error `success = false` dan pesan informatif, sehingga tidak memicu kebocoran data (information leakage).
