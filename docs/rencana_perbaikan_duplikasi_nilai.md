# Rencana Perbaikan Duplikasi Nilai (Mencegah Nilai > 100)

## Latar Belakang
Ditemukan bahwa nilai siswa pada rapor bisa melebihi 100 karena sistem menjumlahkan dua baris data untuk satu pertemuan yang sama: satu baris dengan kategori yang benar (contoh: 'Tengah Semester') dan satu baris dengan kategori kosong.

## Tujuan
1.  Mengabaikan nilai dengan kategori kosong dalam perhitungan rapor.
2.  Mencegah pembuatan data baru dengan kategori kosong melalui validasi input dan import.
3.  Membersihkan data kategori kosong yang sudah ada di database.

## Rencana Aksi

### 1. Modifikasi Perhitungan (NilaiRaporController)
Sistem akan diperbarui untuk mengabaikan data yang tidak memiliki kategori.
*   **File:** `backend/app/Controllers/GuruMapel/NilaiRaporController.php`
*   **Perubahan:** Menghapus klausul `orWhere('kategori', '')` dan `orWhere('kategori', null)` pada method `getData()` dan `syncNilai()`.

### 2. Validasi Input & Import (NilaiFormatifController)
Memastikan setiap data yang masuk memiliki kategori yang valid.
*   **File:** `backend/app/Controllers/GuruMapel/NilaiFormatifController.php`
*   **Perubahan:**
    *   **saveNilai:** Menambahkan pengecekan ketat agar `kategori` selalu terisi. Jika ditemukan data lama dengan kategori kosong untuk siswa/mapel/pertemuan yang sama, data lama tersebut akan dihapus atau diperbarui.
    *   **importExcel:** Menambahkan validasi kategori pada proses parsing Excel. Jika kategori tidak ditentukan, sistem akan menolak atau memberikan nilai default yang valid (misal: 'Tengah Semester' atau 'Akhir Semester' sesuai konteks).
    *   **importExcelAll:** Memastikan pemetaan kategori konsisten untuk semua kolom pertemuan.

### 3. Pembersihan Database (Cleanup Script)
Menjalankan script untuk merapikan data yang sudah terlanjur duplikat di seluruh kelas.
*   **Logic:** 
    *   Cari baris dengan `kategori = ''`.
    *   Jika ada baris identik dengan kategori terisi, pindahkan catatan ke baris tersebut lalu hapus baris kategori kosong.
    *   Jika tidak ada baris identik, update baris tersebut menjadi kategori yang sesuai (berdasarkan konteks semester/tahun ajaran).

## Dampak
*   Nilai pada dashboard akan kembali normal (maksimal 100).
*   Data di database menjadi lebih bersih dan konsisten.
*   Menghindari kebingungan guru saat melihat nilai yang tidak masuk akal.

---
**Status:** Menunggu Persetujuan
**Dibuat oleh:** Antigravity AI
**Tanggal:** 2026-05-12
