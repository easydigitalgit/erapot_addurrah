# Panduan Restorasi Fitur Absensi (Wali Kelas)

Dokumen ini menjelaskan cara mengembalikan (revert) fitur Absensi ke versi aslinya (v1) jika terjadi masalah setelah modifikasi.

## Lokasi Backup
Seluruh file backup disimpan di: `backups/fitur-absensi-v1/`

## Daftar File yang Di-backup
1. **Controller:** `backend/app/Controllers/WaliKelas/AbsensiKelasController.php`
2. **Model:** `backend/app/Models/AbsensiHarianModel.php`
3. **View:** `backend/app/Views/WaliKelas/absensi-kelas.php`
4. **Assets JS:** `backend/public/assets/js/WaliKelas/absensi-kelas.js`
5. **Assets CSS:** `backend/public/assets/css/WaliKelas/absensi-kelas.css`
6. **Language (ID):** `backend/app/Language/id/WaliKelas/Absensi.php`
7. **Language (EN):** `backend/app/Language/en/WaliKelas/Absensi.php`
8. **Migrations:** 
    - `backend/app/Database/Migrations/2026-03-11-000008_AbsensiHarian.php`
    - `backend/app/Database/Migrations/2026-03-11-000008_RekapAbsensi.php`

## Langkah-langkah Restorasi

### 1. Mengembalikan Kode Sumber
Salin kembali file dari folder backup ke lokasi aslinya. Anda bisa menggunakan perintah berikut di terminal:

```powershell
# Copy file backend
cp "backups/fitur-absensi-v1/backend/app/Controllers/WaliKelas/AbsensiKelasController.php" "backend/app/Controllers/WaliKelas/"
cp "backups/fitur-absensi-v1/backend/app/Models/AbsensiHarianModel.php" "backend/app/Models/"
cp "backups/fitur-absensi-v1/backend/app/Views/WaliKelas/absensi-kelas.php" "backend/app/Views/WaliKelas/"
cp "backups/fitur-absensi-v1/backend/app/Language/id/WaliKelas/Absensi.php" "backend/app/Language/id/WaliKelas/"
cp "backups/fitur-absensi-v1/backend/app/Language/en/WaliKelas/Absensi.php" "backend/app/Language/en/WaliKelas/"

# Copy file aset
cp "backups/fitur-absensi-v1/backend/public/assets/js/WaliKelas/absensi-kelas.js" "backend/public/assets/js/WaliKelas/"
cp "backups/fitur-absensi-v1/backend/public/assets/css/WaliKelas/absensi-kelas.css" "backend/public/assets/css/WaliKelas/"
```

### 2. Mengembalikan Struktur & Data Database
Jika struktur tabel berubah, Anda perlu mengembalikan tabel `absensi_harian` dan `rekap_absensi` menggunakan file SQL yang disediakan.

**Peringatan:** Ini akan menghapus data absensi yang baru saja diinput setelah backup dibuat.

Gunakan perintah berikut (sesuaikan dengan kredensial database Anda):
```bash
mysql -u dev -p12345678 raporsmpit < backups/fitur-absensi-v1/database/absensi_tables.sql
```

### 3. Verifikasi
Setelah restorasi selesai, pastikan:
1. Akses menu **Wali Kelas > Absensi Kelas** berjalan normal.
2. Data absensi muncul kembali sesuai dengan kondisi saat backup dilakukan.
3. Tidak ada error 404 atau file JS/CSS yang hilang di console browser.

---
**Dibuat pada:** 2026-05-12
**Oleh:** Antigravity AI
