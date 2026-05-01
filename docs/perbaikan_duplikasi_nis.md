# Rencana Perbaikan Duplikasi NIS (Nomor Induk Siswa)

Dokumen ini merinci langkah-langkah untuk memperbaiki data NIS yang duplikat pada tabel `siswa` dan menyelaraskan logika pembuatan NIS di sistem agar tetap konsisten.

## Masalah Utama
Berdasarkan [laporan_duplikasi_nis.md](file:///d:/xampp/htdocs/erapoteasy/docs/laporan_duplikasi_nis.md), ditemukan 10 pasang siswa yang memiliki NIS yang sama pada prefix `09.25.xxxx`. Hal ini menyebabkan kegagalan pada proses import nilai karena sistem menggunakan NIS sebagai identitas kunci.

Selain itu, terdapat ketidakkonsistenan antara:
1.  **Data Lama:** Menggunakan 4 digit nomor urut (contoh: `09.25.0046`).
2.  **Kode Baru:** `SiswaController.php` saat ini diatur untuk menghasilkan 5 digit nomor urut (contoh: `09.25.00047`).

## Proposed Changes

### 1. Database (Pembersihan Data)
Melakukan re-sequence (pengurutan ulang) NIS untuk seluruh siswa dengan prefix `09.25.` agar unik dan urut berdasarkan ID pendaftaran mereka.

#### SQL Update Script
Gunakan skrip berikut di phpMyAdmin (VPS):

```sql
-- 1. Backup data NIS sebelum diubah (Opsional tapi direkomendasikan)
-- CREATE TABLE siswa_backup_nis AS SELECT id, nis FROM siswa;

-- 2. Reset counter dan update NIS secara berurutan (4 digit)
SET @counter := 0;
UPDATE siswa 
SET nis = CONCAT('09.25.', LPAD(@counter := @counter + 1, 4, '0'))
WHERE nis LIKE '09.25.%'
ORDER BY id ASC;

-- 3. Verifikasi apakah masih ada duplikat
SELECT nis, COUNT(*) as jumlah, GROUP_CONCAT(nama_lengkap) as siswa
FROM siswa
GROUP BY nis
HAVING jumlah > 1;
```

> [!IMPORTANT]
> Skrip di atas menggunakan **4 digit** (`LPAD(..., 4, '0')`) agar sesuai dengan format mayoritas data yang sudah ada di sekolah.

### 2. Backend (Sinkronisasi Logika)
Memperbarui `SiswaController.php` agar menghasilkan 4 digit nomor urut, bukan 5 digit, demi menjaga konsistensi dengan data yang sudah ada.

#### Perubahan Kode di `SiswaController.php`:
Ubah format padding dari `%05d` menjadi `%04d` pada fungsi `store()` dan `generateNextNis()`.

---

## Tahapan Eksekusi
1.  **Backup Database:** Selalu lakukan ekspor tabel `siswa` sebelum menjalankan skrip SQL.
2.  **Jalankan SQL:** Paste skrip di atas ke tab SQL di phpMyAdmin.
3.  **Update Kode:** Terapkan perubahan pada `SiswaController.php`.
4.  **Verifikasi:** Coba tambahkan 1 siswa baru di dashboard admin dan cek apakah NIS yang dihasilkan adalah `09.25.[NomorSelanjutnya]` (dengan 4 digit).
