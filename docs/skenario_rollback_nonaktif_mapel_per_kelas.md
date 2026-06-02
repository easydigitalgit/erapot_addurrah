# Skenario Rollback Fitur Nonaktifkan Mapel Per Kelas

Dokumen ini menjelaskan langkah-langkah untuk membatalkan (rollback) implementasi fitur penonaktifan mata pelajaran per kelas (rombel) jika ditemukan masalah atau ketidaksesuaian.

## 1. Direktori Backup File
Sebelum implementasi dilakukan, salinan asli dari file-file yang akan dimodifikasi disimpan di:
`d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\`

Daftar file yang dibackup:
1. `backend/app/Controllers/Admin/MataPelajaranController.php`
2. `backend/app/Views/admin/mata-pelajaran.php`
3. `backend/public/assets/js/Admin/mata-pelajaran.js`
4. `backend/app/Controllers/WaliKelas/PreviewRaporController.php`
5. `backend/app/Controllers/Admin/CetakRaporController.php`
6. `backend/app/Controllers/OrangTua/AkademikController.php`

## 2. Langkah-Langkah Rollback

### Langkah A: Restorasi File Asli (Rollback Code)
Kembalikan file-file di atas dari folder backup ke lokasi aslinya dengan menimpa (overwrite) file hasil implementasi.

Perintah PowerShell/CMD untuk menyalin kembali file asli:
```powershell
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\MataPelajaranController.php" -Destination "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\Admin\" -Force
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\mata-pelajaran.php" -Destination "d:\xampp\htdocs\erapoteasy\backend\app\Views\admin\" -Force
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\mata-pelajaran.js" -Destination "d:\xampp\htdocs\erapoteasy\backend\public\assets\js\Admin\" -Force
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\PreviewRaporController.php" -Destination "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\WaliKelas\" -Force
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\CetakRaporController.php" -Destination "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\Admin\" -Force
Copy-Item -Path "d:\xampp\htdocs\erapoteasy\backup_nonaktif_per_kelas\AkademikController.php" -Destination "d:\xampp\htdocs\erapoteasy\backend\app\Controllers\OrangTua\" -Force
```

### Langkah B: Rollback Database Migration (Rollback DB)
Untuk membatalkan tabel baru `mapel_nonaktif_rombel` di database, jalankan perintah rollback migration CodeIgniter 4 berikut di terminal (pada folder `backend`):

```bash
php spark migrate:rollback
```
*Catatan: Pastikan migration ini adalah migration paling terakhir dijalankan. Jika tidak, Anda juga dapat menghapus tabel secara manual melalui MySQL dengan perintah:*
```sql
DROP TABLE IF EXISTS `mapel_nonaktif_rombel`;
DELETE FROM `migrations` WHERE `class` LIKE '%MapelNonaktifRombel%';
```

### Langkah C: Hapus File Migration Baru
Hapus file migration baru yang telah dibuat:
- `backend/app/Database/Migrations/2026-06-01-000001_MapelNonaktifRombel.php`

---
*Dengan mengikuti skenario di atas, aplikasi akan kembali ke keadaan semula 100% stabil.*
