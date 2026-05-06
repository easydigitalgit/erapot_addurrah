# Rencana Backup Fitur Form Setoran Cepat

Dokumen ini merinci langkah-laki untuk mencadangkan (backup) fitur **Form Setoran Cepat** sebelum dilakukan perbaikan/modifikasi. Hal ini bertujuan agar sistem dapat dikembalikan (reverse) ke kondisi semula dengan cepat jika ditemukan kendala pada versi baru.

## Daftar File yang Terlibat

Berikut adalah daftar file utama yang mengelola logika dan tampilan Form Setoran Cepat:

1.  **View (Tampilan)**
    *   `backend/app/Views/tahfidz/dashboard.php`
2.  **Frontend Logic (JavaScript)**
    *   `backend/public/assets/js/Tahfidz/dashboard.js`
3.  **Backend Logic (Controller)**
    *   `backend/app/Controllers/Tahfidz/DashboardController.php` (Penyiapan data dashboard & modal)
    *   `backend/app/Controllers/Tahfidz/SetoranController.php` (Logika penyimpanan data `save`)

## Langkah-Langkah Backup

### 1. Backup File Source Code
Gunakan perintah terminal berikut untuk membuat folder backup dan menyalin file-file terkait:

```powershell
# Buat direktori backup dengan timestamp
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "d:\xampp\htdocs\erapoteasy\backups\setoran_cepat_$timestamp"
New-Item -ItemType Directory -Path $backupDir

# Salin file ke folder backup
Copy-Item "d:\xampp\htdocs\erapoteasy\backend\app\Views\tahfidz\dashboard.php" -Destination $backupDir
Copy-Item "d:\xampp\htdocs\erapoteasy\backend\public\assets\js\Tahfidz\dashboard.js" -Destination $backupDir
Copy-Item "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\Tahfidz\DashboardController.php" -Destination $backupDir
Copy-Item "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\Tahfidz\SetoranController.php" -Destination $backupDir

Write-Host "Backup file berhasil disimpan di: $backupDir"
```

### 2. Referensi Struktur Database (Tabel `setoran_tahfidz`)

Berikut adalah struktur kolom tabel `setoran_tahfidz` saat ini untuk referensi jika terjadi perubahan skema:

| Field | Type | Null | Default |
| :--- | :--- | :--- | :--- |
| `id` | int | NO | |
| `siswa_id` | int | NO | |
| `guru_id` | int | NO | |
| `tanggal` | date | NO | |
| `jenis_setoran` | enum('Ziyadah','Murojaah') | NO | Ziyadah |
| `juz_id` | int | YES | |
| `surah_id` | int | YES | |
| `surah` | varchar(100) | NO | |
| `ayat` | varchar(50) | NO | |
| `predikat` | enum('Sangat Lancar','Lancar','Kurang Lancar','Belum Hafal') | NO | Lancar |
| `nilai` | int | YES | 0 |
| `catatan` | text | YES | |
| `created_at` | datetime | NO | CURRENT_TIMESTAMP |
| `updated_at` | datetime | YES | CURRENT_TIMESTAMP |
| `nilai_hfl` | int | YES | 0 |
| `nilai_hrf` | int | YES | 0 |
| `nilai_m` | int | YES | 0 |
| `nilai_t` | int | YES | 0 |

### 3. Langkah-Langkah Backup Database

Jika perbaikan melibatkan perubahan skema database, cadangkan tabel `setoran_tahfidz` dengan perintah SQL berikut:

```sql
-- Jalankan di phpMyAdmin atau terminal MySQL
CREATE TABLE setoran_tahfidz_backup_20260506 AS SELECT * FROM setoran_tahfidz;
```

## Prosedur Revert (Pengembalian)

Jika ingin mengembalikan ke kondisi semula, ikuti langkah ini:

1.  Buka folder backup yang telah dibuat (misal: `backups/setoran_cepat_20260506_133400`).
2.  Salin kembali file-file di dalamnya ke lokasi aslinya (timpa file yang ada).
3.  Refresh browser (tekan `Ctrl + F5` untuk memastikan file JS terbaru dimuat).

---
*Dibuat oleh: Antigravity AI*
*Tanggal: 06 Mei 2026*
