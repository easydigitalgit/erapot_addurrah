# Panduan Lengkap: Penggunaan Skrip CLI Snapshot & Restore Data Siswa & Rombel

**File Skrip:** `capture_siswa_rombel.php`  
**Lokasi Skrip:** Root direktori project (`d:\laragon\htdocs\erapoteasy\capture_siswa_rombel.php`)  
**Tujuan:** Mengambil cadangan data (*snapshot*) siswa, rombel, dan akun terkait secara instan via CLI, serta memulihkannya (*restore*) dengan cepat kapan saja jika terjadi insiden siswa hilang/berkurang di server VPS.

---

## 1. Keunggulan Skrip

1. **Otomatis Membaca Kredensial Database:**
   Membaca setting host, nama database, username, dan password langsung dari file `backend/.env` (atau `.env`). Anda tidak perlu memasukkan password MySQL secara manual saat menjalankan perintah.
2. **Format SQL Idempoten (`INSERT ... ON DUPLICATE KEY UPDATE`):**
   File snapshot yang dihasilkan aman dieksekusi berulang kali. Jika data siswa sudah ada, sistem hanya memperbarui kolom penempatan rombel (`rombel_id`) dan status aktifnya tanpa merusak data lain atau menimbulkan error *duplicate key*.
3. **Cakupan 6 Tabel Inti Akademik:**
   - `tahun_ajaran` (Konteks semester & status aktif)
   - `rombel` (Daftar kelas & wali kelas)
   - `siswa` (Data master siswa, NIS, NISN, NIK, dan rombel flat)
   - `anggota_rombel` (Riwayat penempatan kelas semester aktif)
   - `orangtua_wali` (Data ayah, ibu, wali, dan nomor kontak)
   - `users` (Akun login untuk role Siswa `role_id=3` dan Orang Tua `role_id=4`)
4. **Tabel Rekap Otomatis:**
   Menampilkan ringkasan jumlah siswa aktif per kelas langsung di terminal saat snapshot selesai diambil.
5. **Dukungan Restore 1 Perintah:**
   Menyediakan opsi flag `--restore` untuk mengembalikan database langsung dari terminal PHP.

---

## 2. Cara Penggunaan di Server VPS

### Langkah 1: Memasukkan File ke VPS

Jika menggunakan Git:
```bash
# Di komputer lokal:
git add capture_siswa_rombel.php docs/panduan_capture_dan_restore_siswa_rombel_cli.md
git commit -m "feat: add CLI capture and restore script for siswa and rombel"
git push

# Di terminal VPS (masuk ke root folder web e-Rapor):
git pull
```
*(Atau upload file `capture_siswa_rombel.php` secara langsung via FileZilla / SCP / cPanel File Manager ke root direktori web Anda di VPS).*

---

### Langkah 2: Mengambil Snapshot (Capture Data Saat Ini)

Jalankan perintah berikut di terminal VPS:

```bash
php capture_siswa_rombel.php
```

#### Output yang Dihasilkan:
- File SQL snapshot otomatis disimpan pada direktori:
  ```text
  backups/snapshots/snapshot_siswa_rombel_YYYYMMDD_HHMMSS.sql
  ```
- Terminal akan menampilkan rekap jumlah siswa aktif per rombel:
  ```text
  ====================================================================
   📸 MEMULAI CAPTURE SNAPSHOT DATA SISWA & ROMBEL
   Database: raporsmpit @ localhost
   Waktu   : 2026-10-26 16:40:51
  ====================================================================
  -> Menyimpan tabel tahun_ajaran...
  -> Menyimpan tabel rombel...
  -> Menyimpan tabel siswa...
  -> Menyimpan tabel anggota_rombel...
  -> Menyimpan tabel orangtua_wali...
  -> Menyimpan tabel users (khusus role siswa & orang tua)...

  ====================================================================
   ✅ SNAPSHOT BERHASIL DIBUAT!
   📁 Lokasi File: backups/snapshots/snapshot_siswa_rombel_20261026_164051.sql
   📦 Ukuran File: 3.3 MB
  ====================================================================

  📊 REKAP JUMLAH SISWA AKTIF PER ROMBEL SAAT CAPTURE:
  ------------------------------------------------------------
  ID    | Tingkat         | Nama Rombel        | Siswa Aktif
  ------------------------------------------------------------
  77    | VII             | Amazonite          | 26        
  80    | VII             | Carnelian          | 33        
  78    | VII             | Opal               | 26        
  81    | VII             | Ruby               | 33        
  79    | VII             | Zamrud             | 26        
  72    | VIII            | Granit             | 32        
  74    | VIII            | Intan              | 22        
  75    | VIII            | Kyanite            | 22        
  76    | VIII            | Safir              | 22        
  73    | VIII            | Topaz              | 33        
  69    | IX              | Biduri             | 30        
  70    | IX              | Crystal            | 28        
  71    | IX              | Diamond            | 31        
  68    | IX              | Emerald            | 29        
  67    | IX              | Fairuz             | 28        
  ------------------------------------------------------------
   TOTAL SISWA AKTIF DI SELURUH ROMBEL: 421 Siswa
  ====================================================================
  ```

---

### Langkah 3: Merestore Data dengan Cepat Kapan Saja

Jika suatu saat terjadi masalah (misal siswa berkurang setelah impor Excel atau akun terhapus), jalankan salah satu opsi restore berikut:

#### Opsi A: Menggunakan CLI PHP (Paling Praktis)
```bash
php capture_siswa_rombel.php --restore=backups/snapshots/nama_file_snapshot.sql
```
*Contoh:*
```bash
php capture_siswa_rombel.php --restore=backups/snapshots/snapshot_siswa_rombel_20261026_164051.sql
```

#### Opsi B: Menggunakan CLI MySQL
```bash
mysql -u [db_user] -p [db_name] < backups/snapshots/nama_file_snapshot.sql
```
*Contoh:*
```bash
mysql -u root -p raporsmpit < backups/snapshots/snapshot_siswa_rombel_20261026_164051.sql
```

#### Opsi C: Menggunakan phpMyAdmin
1. Buka **phpMyAdmin** > Pilih database `raporsmpit`.
2. Masuk ke tab **Import**.
3. Klik **Choose File** lalu pilih file snapshot dari folder `backups/snapshots/`.
4. Klik tombol **Kirim / Import**.

---

## 3. Opsi Parameter Tambahan (Kustomisasi Koneksi)

Jika ingin menjalankan skrip ke database lain atau host berbeda tanpa mengubah `.env`, gunakan flag parameter berikut:

| Parameter | Deskripsi | Contoh |
|---|---|---|
| `--host=` | Menentukan hostname MySQL | `php capture_siswa_rombel.php --host=127.0.0.1` |
| `--db=` | Menentukan nama database | `php capture_siswa_rombel.php --db=raporsmpit_backup` |
| `--user=` | Menentukan username MySQL | `php capture_siswa_rombel.php --user=admin` |
| `--pass=` | Menentukan password MySQL | `php capture_siswa_rombel.php --pass=Rahasia123` |
| `--port=` | Menentukan port MySQL | `php capture_siswa_rombel.php --port=3306` |
| `--restore=` | Menjalankan mode restore dari file SQL | `php capture_siswa_rombel.php --restore=file.sql` |
| `--help` | Menampilkan panduan bantuan CLI | `php capture_siswa_rombel.php --help` |

---

## 4. Tips: Otomatisasi Snapshot Berkala via Cron Job VPS

Untuk memastikan data siswa selalu memiliki cadangan segar setiap hari secara otomatis, Anda bisa menambahkan cron job di server VPS:

1. Buka crontab:
   ```bash
   crontab -e
   ```
2. Tambahkan baris berikut untuk mengambil snapshot otomatis setiap hari pukul 02:00 dini hari:
   ```bash
   0 2 * * * /usr/bin/php /var/www/erapot/capture_siswa_rombel.php > /dev/null 2>&1
   ```
   *(Sesuaikan path `/var/www/erapot/` dengan path instalasi web Anda di VPS).*
