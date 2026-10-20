# Analisis dan Perbaikan: Error Duplicate Entry Orang Tua / Wali

## 1. Temuan Masalah

Beberapa guru/staf operator melaporkan kegagalan saat menambahkan data siswa baru dengan notifikasi kesalahan:
> **"Gagal menyimpan profil Wali. Duplicate entry '196' for key 'orangtua_wali.user_id'"**

---

## 2. Investigasi & Akar Masalah (Root Cause)

### A. Alur Relasi Akun Orang Tua (Kakak-Beradik)
1. User ID `196` adalah akun login wali murid di tabel `users` dengan:
   - **Username**: `085275565259` (Nomor HP)
   - **Role ID**: `4` (Orang Tua / Wali)
   - **Profil Sebelumnya**: Terhubung ke siswa ID `68` atas nama orang tua *MHD TRIYA RAMDANI*.
2. Pada [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php#L296-L350), sistem dirancang untuk mendukung **Shared Account** (kakak-beradik berbagi satu akun login orang tua yang sama berdasarkan nomor HP).
3. Ketika siswa kedua didaftarkan dengan nomor HP orang tua yang sama, sistem berhasil menemukan akun `users` yang sudah ada (`user_id = 196`).
4. Sesuai arsitektur data siswa, sistem kemudian mencoba menyimpan profil wali murid untuk siswa baru tersebut ke tabel `orangtua_wali`:
   ```php
   $dataOrtu['siswa_id'] = $siswaId; // ID Siswa baru
   $dataOrtu['user_id']  = 196;      // ID User Ortu yang sama
   $db->table('orangtua_wali')->insert($dataOrtu);
   ```

### B. Kendala Constraint Database
Pada skema awal database (`raporsmpit.sql`):
```sql
ALTER TABLE `orangtua_wali`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email_ortu` (`email_ortu`),
  ADD KEY `siswa_id` (`siswa_id`);
```
- Kolom `user_id` memiliki indeks **`UNIQUE`**, yang secara ketat membatasi satu `user_id` hanya boleh muncul tepat 1 kali di tabel `orangtua_wali`.
- Kolom `email_ortu` juga memiliki indeks **`UNIQUE`**, yang akan memicu kegagalan serupa jika siswa bersaudara memiliki email orang tua yang sama atau dikosongkan.
- Akibatnya, saat query `INSERT` kedua dijalankan dengan `user_id = 196`, MySQL menolak transaksi dan memicu error `Duplicate entry '196' for key 'orangtua_wali.user_id'`.

---

## 3. Solusi & Perubahan yang Diterapkan

Untuk mendukung fitur relasi saudara kandung (kakak-beradik) tanpa melanggar batasan basis data, batasan `UNIQUE` pada `user_id` dan `email_ortu` di tabel `orangtua_wali` diubah menjadi indeks biasa (`KEY`/non-unique).

### 1. Pembaruan Skema Basis Data Dasar
File: [raporsmpit.sql](file:///d:/laragon/htdocs/erapoteasy/raporsmpit.sql)
```diff
 ALTER TABLE `orangtua_wali`
   ADD PRIMARY KEY (`id`),
-  ADD UNIQUE KEY `user_id` (`user_id`),
-  ADD UNIQUE KEY `email_ortu` (`email_ortu`),
+  ADD KEY `user_id` (`user_id`),
   ADD KEY `siswa_id` (`siswa_id`);
```

### 2. Script Migrasi Otomatis & Idempotent (Helper VPS/Production)
File: [backend/update_indexes.php](file:///d:/laragon/htdocs/erapoteasy/backend/update_indexes.php)
- Membaca konfigurasi koneksi database dari file `.env` secara dinamis.
- **Safety First**: Menambahkan indeks non-unique `idx_orangtua_wali_user_id` terlebih dahulu agar relasi *Foreign Key* `orangtua_wali_ibfk_1` tetap terpenuhi saat indeks unik dihapus.
- Menghapus indeks `UNIQUE` pada `user_id` dan `email_ortu`.
- Dirancang idempotent (aman dijalankan berulang kali tanpa memicu fatal exception).

### 3. File Migrasi CodeIgniter 4
File: [backend/app/Database/Migrations/2026-10-20-000001_RemoveUniqueFromOrangtuaWali.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Database/Migrations/2026-10-20-000001_RemoveUniqueFromOrangtuaWali.php)
- Menyediakan definisi migrasi resmi framework jika database dikelola melalui perintah `php spark migrate`.

### 4. Alat Diagnostik & Pengujian
File: [backend/check_indexes.php](file:///d:/laragon/htdocs/erapoteasy/backend/check_indexes.php)
- Menginspeksi struktur indeks aktif tabel `orangtua_wali`.
- Melakukan simulasi transaksi penambahan data profil wali dengan `user_id = 196`.

---

## 4. Hasil Verifikasi & Pengujian

Hasil pengujian eksekusi [check_indexes.php](file:///d:/laragon/htdocs/erapoteasy/backend/check_indexes.php):
```text
=== DIAGNOSTIC REPORT: orangtua_wali ===

1. Current Indexes on orangtua_wali:
   - PRIMARY | Col: id | UNIQUE
   - siswa_id | Col: siswa_id | NON-UNIQUE
   - idx_orangtua_wali_user_id | Col: user_id | NON-UNIQUE

2. Check User ID 196 in users and orangtua_wali:
   - ID: 196
   - Username: 085275565259
   - Role ID: 4
   - Record terdaftar: Siswa ID 68 (MHD TRIYA RAMDANI)

3. Test Simulation Insert Multiple Students with Parent user_id = 196:
   [SUCCESS] Multiple records with user_id = 196 can be inserted without constraint violation!

=== END DIAGNOSTIC REPORT ===
```

---

## 5. Panduan Penerapan di Server Produksi / VPS

Untuk mengatasi kendala ini pada server produksi atau VPS yang sedang digunakan oleh para guru:

### Opsi A: Menggunakan Script PHP Helper (Direkomendasikan)
Masuk ke terminal server dan jalankan:
```bash
cd /path/ke/proyek/backend
php update_indexes.php
```

### Opsi B: Menggunakan Perintah SQL Langsung (phpMyAdmin / MySQL CLI)
Jalankan kueri berikut pada database produksi:
```sql
-- 1. Tambah index non-unique terlebih dahulu agar Foreign Key tetap valid
ALTER TABLE orangtua_wali ADD INDEX idx_orangtua_wali_user_id (user_id);

-- 2. Hapus batasan unique index lama
ALTER TABLE orangtua_wali DROP INDEX user_id;
ALTER TABLE orangtua_wali DROP INDEX email_ortu;
```
