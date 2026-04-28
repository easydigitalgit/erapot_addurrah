# Analisa Filtering Halaman Mapping Guru Mapel

Dokumen ini berisi analisa mengenai sistem filtering pada halaman Mapping Guru Mapel (`/admin/mapping-mapel`) untuk memastikan integritas data dan alur kerja yang efisien.

## 1. Alur Filtering Saat Ini

Sistem filtering pada halaman ini terbagi menjadi dua mekanisme:

### A. Server-Side Filtering (Tahun & Semester)
*   **Trigger**: Perubahan pada dropdown "Filter Tahun & Semester".
*   **Mekanisme**: Menggunakan `onchange="window.location.href='?ta=' + this.value"`. Ini memicu *full page reload*.
*   **Backend**: Controller `MappingMapelController::index` menangkap parameter `ta`, lalu memfilter data dari database berdasarkan `tahun_ajaran_id`.
*   **Status**: **Sesuai Harapan.** Data dasar (source of truth) diubah hanya melalui filter ini.

### B. Client-Side Filtering (Tingkat, Rombel, Mapel, Guru, & Pencarian)
*   **Trigger**: Perubahan pada dropdown filter lainnya atau pengetikan di kolom pencarian.
*   **Mekanisme**: Fungsi JavaScript `applyFilters()` memproses array `mappingData` (yang sudah dimuat di browser) dan memperbarui tampilan tabel secara instan tanpa memuat ulang halaman.
*   **Status**: **Efisien**, namun terdapat redunansi kode.

---

## 2. Temuan Analisa

Berdasarkan instruksi bahwa *"isi table hanya bisa berubah saat filter tahun & semester di ubah"*, berikut adalah poin-poin penting:

### 1. Redundansi & Konflik pada Filter Tahun
Di file `mapping-mapel.js`, terdapat event listener untuk `filterTahun` yang memanggil `applyFilters()`. Padahal di file PHP, elemen tersebut sudah memiliki atribut `onchange` untuk reload halaman.
*   **Dampak**: Terjadi dua proses bersamaan. Saat browser mulai berpindah halaman, JS mencoba memfilter data lama.
*   **Rekomendasi**: Hapus listener JS untuk `filterTahun` karena reload halaman sudah menjamin data yang ditampilkan adalah data yang benar dari server.

### 2. Logika Filtering Tahun di JavaScript
Di dalam fungsi `applyFilters()`, terdapat pengecekan:
```javascript
const matchTahun = !tahun || (item.tahunAjaran && item.tahunAjaran.includes(tahun)) || ...
```
*   **Masalah**: Variabel `tahun` berisi **ID** (integer), sedangkan `item.tahunAjaran` berisi **String** (misal: "2025/2026 (Genap)"). Pengecekan `.includes(tahun)` di sini secara teknis tidak akurat dan tidak diperlukan karena data yang dikirim dari server sudah pasti difilter berdasarkan tahun tersebut.

### 3. Konsistensi Statistik (Dashboard Cards)
Angka statistik di bagian atas (Total Guru, Total Mapel, dll) dihasilkan melalui PHP.
*   **Kondisi**: Angka-angka ini hanya berubah jika halaman di-reload (melalui filter Tahun & Semester).
*   **Kesesuaian**: Hal ini sudah sejalan dengan prinsip bahwa "isi table (dan konteks data) hanya berubah saat filter tahun & semester di ubah".

---

## 4. Hasil Perbaikan (Implemented)

Berdasarkan analisa di atas, perbaikan telah dilakukan pada tanggal **28 April 2026** dengan rincian sebagai berikut:

### A. Sisi Frontend (JS & View)
1.  **Pembersihan Konflik**: Event listener untuk `filterTahun` di JavaScript telah dihapus. Sekarang perubahan tahun sepenuhnya ditangani oleh reload halaman (PHP).
2.  **Optimasi `applyFilters()`**: Pengecekan `matchTahun` di sisi client telah dihapus karena redundan. Dataset yang ada di memori browser sudah pasti milik tahun ajaran yang aktif.
3.  **Statistik Dinamis (Real-time)**: 
    *   Ditambahkan fungsi `updateStats()` yang memperbarui angka pada kartu statistik (Total Guru, Total Mapel, Mapping Aktif) secara *real-time* saat user melakukan filtering.
    *   Elemen pada `mapping-mapel.php` telah diberikan ID unik (`stat-total-guru`, dll) untuk mendukung fitur ini.
4.  **Fitur Reset Filter**: Ditambahkan tombol **"Reset Semua Filter"** di samping checkbox "Tampilkan Hanya Yang Aktif" untuk memudahkan user kembali ke tampilan default tanpa reload halaman.

### B. Arsitektur Akhir
| Filter | Tipe | Aksi |
| :--- | :--- | :--- |
| **Tahun & Semester** | Server-Side | Reload halaman & Ambil data baru dari DB. |
| **Tingkat / Rombel** | Client-Side | Filter baris tabel & Update angka statistik secara lokal. |
| **Guru / Mapel** | Client-Side | Filter baris tabel & Update angka statistik secara lokal. |
| **Pencarian** | Client-Side | Mencari di kolom Nama, NIK, Mapel, & Rombel secara instan. |

## 5. Kesimpulan
Sistem filtering sekarang bekerja secara harmonis. Tahun Ajaran berfungsi sebagai "Konteks Data" (Source of Truth), sementara filter lainnya berfungsi sebagai "Navigasi Tampilan" (View Filter) yang responsif dan informatif.

*Dokumen ini diperbarui terakhir pada: 2026-04-28*
