# Analisa Komprehensif: Masalah Kategori Nilai pada Upload Kolektif & Monitoring Admin

## 1. Ringkasan Masalah
Data nilai yang di-upload melalui fitur **Nilai Kolektif** gagal ditampilkan pada kolom "Rata-rata Harian" dan "Rata-rata UH" di halaman **Monitoring Nilai Siswa (Admin)**. Meskipun data ada di database, nilai rata-rata hanya menampilkan tanda `-`.

## 2. Analisa Alur Masalah (Root Cause)

### A. Proses Upload Nilai Kolektif (/guru/nilai-kolektif)
1.  **Pembacaan Excel**: Saat file Excel di-upload, controller `NilaiKolektifController` membaca sel **C6** untuk menentukan kategori (Tengah atau Akhir Semester).
2.  **Logika Penentuan**: Kode menggunakan logika:
    - Jika ada kata "tengah" maka `$kategori_db = "Tengah"`.
    - Jika tidak ada maka `$kategori_db = "Akhir"`.
3.  **Benturan dengan Database (ENUM Mismatch)**:
    - Tabel `nilai_formatif` memiliki kolom `kategori` dengan tipe data **ENUM('Tengah Semester', 'Akhir Semester')**.
    - Aplikasi mencoba memasukkan string `"Tengah"`. Karena `"Tengah"` tidak ada dalam daftar ENUM (yang ada adalah `"Tengah Semester"`), MySQL menolak nilai tersebut.
    - **Hasil Akhir**: Database menyimpan nilai **string kosong (`""`)** sebagai fallback/error handling untuk nilai ENUM yang tidak valid.

### B. Kegagalan Tampilan di Dashboard Monitoring
1.  **Filter SQL yang Ketat**: Pada controller Monitoring Admin (`MonitoringNilaiSiswaController`), sistem mencari data dengan perintah:
    `WHERE kategori = 'Tengah'` (atau `'Akhir'`).
2.  **Kegagalan Pencocokan**: 
    - Query mencari `'Tengah'`.
    - Database berisi `""` (hasil error upload tadi).
    - Data tidak ditemukan, sehingga perhitungan rata-rata menjadi `0` dan ditampilkan sebagai `-`.
3.  **Ketidakkonsistenan**: Bahkan jika upload berhasil memasukkan `"Tengah Semester"`, dashboard admin akan tetap gagal karena ia mencari string `"Tengah"`. Terjadi ketidakkonsistenan antara definisi data di Database, Controller Guru, dan Controller Admin.

## 3. Rencana Perbaikan (Plan)

### Fase 1: Standarisasi String Kategori
Semua rujukan kategori di seluruh aplikasi (Guru, Admin, dan Import) akan diseragamkan mengikuti standar Database ENUM:
- **Tengah Semester**
- **Akhir Semester**

**File yang akan diubah:**
- `app/Controllers/GuruMapel/NilaiKolektifController.php` (Fungsi `importExcel`)
- `app/Controllers/GuruMapel/NilaiFormatifController.php` (Fungsi `importExcel` & `getGrades`)
- `app/Controllers/Admin/MonitoringNilaiSiswaController.php` (Fungsi `_getRekapData`)

### Fase 2: Perbaikan Data (Data Repair)
Menjalankan perintah SQL untuk memperbaiki data yang sudah terlanjur "cacat" di database:
```sql
UPDATE nilai_formatif SET kategori = 'Tengah Semester' WHERE kategori = '' OR kategori = 'Tengah';
UPDATE nilai_formatif SET kategori = 'Akhir Semester' WHERE kategori = 'Akhir';
```

### Fase 3: Fleksibilitas Query (Defensive Programming)
Mengubah query pencarian agar lebih "pintar" dengan mencari kemungkinan variasi string (Tengah vs Tengah Semester) untuk mencegah masalah serupa di masa depan.

## 4. Verifikasi Akhir
1.  Upload ulang nilai kolektif dan pastikan kolom `kategori` di DB terisi `"Tengah Semester"`.
2.  Cek Dashboard Admin dan pastikan nilai rata-rata muncul dengan benar.

---
**Status**: Menunggu Persetujuan Eksekusi
**Dibuat Oleh**: Antigravity AI
**Tanggal**: 04 Mei 2026
