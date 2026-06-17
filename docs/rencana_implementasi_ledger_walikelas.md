# Rencana Teknis: Fitur Ledger Nilai & Ledger Ekskul di Dashboard Wali Kelas

Dokumen ini menjelaskan rencana teknis untuk menambahkan fitur **Ledger Nilai** dan **Ledger Ekskul** ke dalam Dashboard Wali Kelas. Kedua fitur ini akan diadaptasi dari fitur serupa yang ada di Dashboard Admin, dengan pembatasan filter secara ketat agar hanya menampilkan kelas perwalian Wali Kelas yang sedang login.

---

## 1. Konsep & Arsitektur Keamanan

### Keamanan Berbasis Kepemilikan (Class-Level Locking)
Untuk mencegah Wali Kelas mengakses data ledger kelas lain secara tidak sah (ID bypass/parameter tampering):
1. **Identifikasi Kelas Otomatis**: Backend akan mengidentifikasi ID Wali Kelas dari session user ID yang sedang aktif, lalu mencari rombel (kelas) perwaliannya pada tahun ajaran/semester yang dipilih.
2. **Ignoransi/Validasi Input `rombel_id`**: Setiap API request (`get-data` dan `export-excel`) akan memvalidasi bahwa `rombel_id` yang dikirim benar-benar milik Wali Kelas tersebut. Jika tidak cocok (atau jika mencoba memanipulasi parameter), sistem akan menolak akses atau memaksa data di-load hanya untuk kelas miliknya sendiri.

### Fleksibilitas Berdasarkan Tahun Ajaran
Wali Kelas tetap dapat menggunakan dropdown filter **Tahun Ajaran / Semester** untuk melihat riwayat ledger nilai/ekskul di masa lampau. Sistem akan otomatis mendeteksi kelas perwalian guru tersebut pada tahun ajaran masa lampau yang dipilih. Jika guru tidak menjadi Wali Kelas pada tahun ajaran yang dipilih, sistem akan menampilkan pesan pemberitahuan yang informatif.

---

## 2. Rute Baru (`backend/app/Config/Routes.php`)

Menambahkan rute-rute berikut di dalam grup rute `'wali'` yang dilindungi oleh filter `'role:wali_kelas'`:

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

## 3. Komponen Backend

### A. Controller Ledger Nilai (`backend/app/Controllers/WaliKelas/CetakLegerController.php`)
- Mewarisi `WaliKelasBaseController`.
- **`index()`**:
  - Mengambil data guru dari `user_id` di session.
  - Membaca parameter filter `ta` dari URL atau default ke Tahun Ajaran aktif.
  - Mengambil data rombel perwalian guru tersebut untuk tahun ajaran terpilih.
  - Mengirim data ke view `WaliKelas/cetak-leger`.
- **`getData()`** & **`exportExcel()`**:
  - Mengambil `rombel_id` berdasarkan guru yang login dan tahun ajaran yang dipilih.
  - Mengambil data nilai akademik yang terkunci untuk kelas tersebut.
  - Menghitung rata-rata kelas, nilai tertinggi/terendah, ketuntasan, dan peringkat siswa.
  - Menghasilkan respon JSON untuk `getData()` atau file Excel (`.xlsx`) via PhpSpreadsheet untuk `exportExcel()`.

### B. Controller Ledger Ekskul (`backend/app/Controllers/WaliKelas/CetakLegerEkskulController.php`)
- Mewarisi `WaliKelasBaseController`.
- **`index()`**:
  - Mengambil data rombel perwalian guru berdasarkan tahun ajaran terpilih.
  - Mengirim list ekstrakurikuler aktif dan data ke view `WaliKelas/cetak-leger-ekskul`.
- **`getData()`** & **`exportExcel()`**:
  - Mengambil data nilai ekskul siswa di rombel perwalian untuk tahun ajaran/semester yang dipilih.
  - Menghasilkan respon JSON untuk rendering tabel dinamis atau file Excel (`.xlsx`) berisi matriks nilai ekskul.

---

## 4. Komponen Frontend (UI/UX)

### A. Menu Navigasi (`backend/app/Controllers/WaliKelasBaseController.php`)
Menambahkan submenu baru di dalam kategori **Rapor**:
- **Ledger Nilai** (URL: `wali/cetak-leger`)
- **Ledger Ekstrakurikuler** (URL: `wali/cetak-leger-ekskul`)

```php
        $sub_rapor = [
            ['url' => 'wali/preview-rapor', 'label' => lang('Sidebar.preview_rapor_kelas')],
            ['url' => 'wali/tahfidz', 'label' => 'Cetak Rapor Nilai Tahfiz', 'active' => url_is('wali/tahfidz*')],
            ['url' => 'wali/cetak-leger', 'label' => lang('Sidebar.ledger'), 'active' => url_is('wali/cetak-leger*')],
            ['url' => 'wali/cetak-leger-ekskul', 'label' => lang('Sidebar.leger_ekskul'), 'active' => url_is('wali/cetak-leger-ekskul*')],
        ];
```

### B. Views
1. **`backend/app/Views/WaliKelas/cetak-leger.php`**
   - Menampilkan tabel ledger nilai akademik.
   - Pilihan Rombel di-lock (disabled) ke kelas perwalian guru yang sedang aktif.
   - Menggunakan referensi style `assets/css/Admin/cetak-leger.css` untuk estetika premium dan responsif.
2. **`backend/app/Views/WaliKelas/cetak-leger-ekskul.php`**
   - Menampilkan tabel matriks nilai ekstrakurikuler.
   - Pilihan Rombel di-lock (disabled) ke kelas perwalian guru yang sedang aktif.

### C. Assets Javascript
1. **`backend/public/assets/js/WaliKelas/cetak-leger.js`**
   - Diadaptasi dari file admin, dengan URL request yang diarahkan ke endpoint `/wali/cetak-leger/*`.
2. **`backend/public/assets/js/WaliKelas/cetak-leger-ekskul.js`**
   - Diadaptasi dari file admin, dengan URL request yang diarahkan ke endpoint `/wali/cetak-leger-ekskul/*`.

---

## 5. Rencana Pengujian & Verifikasi

1. **Uji Fungsionalitas**:
   - Memastikan menu Ledger Nilai dan Ledger Ekskul muncul di sidebar Wali Kelas.
   - Memastikan dropdown kelas ter-lock secara dinamis hanya untuk kelas perwalian guru yang bersangkutan.
   - Memastikan tombol cetak browser dan ekspor Excel bekerja dengan baik dan menghasilkan data yang valid.
2. **Uji Keamanan**:
   - Memastikan jika user mencoba menembak API `/wali/cetak-leger/get-data` dengan mengganti parameter `rombel_id` kelas lain, backend tetap membatasi query hanya untuk rombel perwalian aslinya atau menolaknya dengan pesan error.
3. **Uji Tampilan & Tema**:
   - Memverifikasi mode gelap (dark mode) dan mode terang pada tabel ledger agar serasi dengan dashboard Wali Kelas.
