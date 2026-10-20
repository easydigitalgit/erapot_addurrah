# Laporan Analisa Berkurangnya Siswa & Panduan Restore di VPS

---

## 1. Ringkasan Eksekutif & Latar Belakang Masalah

Beberapa wali kelas melaporkan terjadinya pengurangan jumlah siswa secara tiba-tiba di kelas mereka pada semester aktif (2026/2027 Ganjil), seperti:
* **Kelas VII Amazonite (Ustadz Adhitya Prayoga)**: Dari 26 siswa menjadi 23 siswa *(hilang 3 siswa: Azka Adhyastha, Bima Adi Brata, Muhammad Fadhlan Idris)*.
* **Kelas VII Carnelian (Ustadzah Tika)**: Dari 33 siswa menjadi 31 siswa *(hilang 2 siswa)*.
* **Kelas VII Zamrud (Ustadz Septian)**: Dari 26 siswa menjadi 24 siswa *(hilang 2 siswa)*.
* **Kelas VII Ruby (Ustadzah Dmia)**: Dari 32/30 siswa menjadi 26 siswa *(hilang 4 siswa)*.
* **Kelas VIII Kyanite (Ustadzah Suri)**: Dari 22 siswa menjadi 21 siswa *(hilang 1 siswa)*.

Setelah dilakukan audit menyeluruh pada basis data, ditemukan korelasi langsung antara insiden ini dengan **40 siswa tanpa `rombel_id`** (Siswa ID 581 s/d 620).

---

## 2. Hasil Audit Basis Data & Temuan Teknis

### A. Mekanisme Tampilan Siswa di Dashboard Wali Kelas
Di controller wali kelas (`app/Controllers/WaliKelas/DaftarSiswaController.php`), daftar siswa diambil dengan query:
```php
$siswa_kelas = $db->table('anggota_rombel ar')
    ->select('siswa.*, users.foto_profil')
    ->join('siswa', 'siswa.id = ar.siswa_id') // <-- INNER JOIN
    ->join('users', 'users.id = siswa.user_id', 'left')
    ->where('ar.rombel_id', $rombel['id'])
    ->where('ar.tahun_ajaran_id', $id_ta)
    ->where('ar.semester', $semester)
    ->where('siswa.status_siswa', 'Aktif')
    ->orderBy('siswa.nama_lengkap', 'ASC')
    ->get()->getResultArray();
```

### B. Temuan Kerusakan Data (Data Inconsistency)
1. **Keanggotaan Kelas Masih Ada di `anggota_rombel`**: Record siswa pada tabel `anggota_rombel` untuk tahun ajaran aktif **masih lengkap**.
2. **Record Siswa Terhapus dari Tabel `siswa`**: ID siswa yang bersangkutan sudah tidak ada di tabel `siswa`.
3. **Akibat INNER JOIN**: Karena record di tabel `siswa` tidak ada, relasi `INNER JOIN` menghasilkan 0 baris untuk siswa tersebut, sehingga siswa otomatis menghilang dari daftar kelas wali kelas.

### C. Daftar Siswa yang Hilang & Terverifikasi
| ID Siswa Asli | Nama Lengkap | Kelas / Rombel | Status di `anggota_rombel` | Status di `siswa` |
| :---: | :--- | :--- | :---: | :---: |
| **536** | **AZKA ADHYASTHA** | VII Amazonite (77) | Ada (ID: 784) | **Hilang** |
| **538** | **BIMA ADI BRATA** | VII Amazonite (77) | Ada (ID: 786) | **Hilang** |
| **548** | **MUHAMMAD FADHLAN IDRIS** | VII Amazonite (77) | Ada (ID: 796) | **Hilang** |
| **437** | Anggitta Lady Azzahra Siregar | VII Carnelian (80) | Ada (ID: 852) | **Hilang** |
| **442** | FIRZANA AR RAQILLA EPSA | VII Carnelian (80) | Ada (ID: 857) | **Hilang** |
| **502** | FATIH SEPTIAN | VII Zamrud (79) | Ada (ID: 820) | **Hilang** |
| **506** | HAZIQ SYAHIR | VII Zamrud (79) | Ada (ID: 824) | **Hilang** |
| **431** | Safana Al Humaira | VIII Kyanite (75) | Ada (ID: 757) | **Hilang** |
| **2, 9, 11, 12, 14** | Oka, Bariq, Elkan, Fahrian, Fatih Elkarim | VIII Granit (72) | Ada | **Hilang** |
| **33, 36, 44..52** | Adabi, Andrian, Fathir, Ghaisan, dll. (10 siswa) | VIII Topaz (73) | Ada | **Hilang** |
| **642, 652, 656, 661** | Siswa Baru Ruby | VII Ruby (81) | Ada | **Hilang** |

---

## 3. Rekonstruksi Kronologi & Akar Masalah (Root Causes)

```
[File Excel Import Bermasalah]
        │  (Kolom tergeser 1 posisi)
        ▼
[Tabel Siswa: ID 581 - 620]
  - NIS = No Urut (1, 2, 3...)
  - NISN = Nama Siswa
  - Nama Lengkap = Gender (L/P)
  - rombel_id = NULL
        │
        ├─ Terjadi duplikasi data antara siswa lama & siswa import rusak
        ▼
[Tindakan Penghapusan Manual / Pembersihan Duplikat]
  - Operator menghapus data siswa melalui tombol "Hapus Siswa"
  - `SiswaController::delete()` hanya menghapus di `users` dan `siswa`
  - Tabel `anggota_rombel` TIDAK TERHAPUS (karena tidak ada FK ON DELETE CASCADE)
        │
        ▼
[Siswa Asli Terhapus, Siswa Rusak Masuk Tanpa Rombel]
  - Di tampilan wali kelas (INNER JOIN), siswa asli langsung lenyap
```

### Akar Masalah:
1. **Import File Excel Kolom Tergeser (12 Oktober 2026 10:05)**:
   File yang diunggah memiliki urutan kolom berbeda dari template resmi. Sistem memasukkan nama siswa ke kolom NISN dan mengosongkan `rombel_id`.
2. **Ketiadaan Constraint `ON DELETE CASCADE` pada `anggota_rombel`**:
   Tabel `anggota_rombel` tidak memiliki foreign key constraint yang otomatis menghapus baris ketika data di tabel `siswa` dihapus.
3. **Bug di Method `SiswaController::delete()`**:
   Controller hanya menjalankan `$db->table('users')->delete()` dan `$siswaModel->delete()`, tanpa menyinkronkan tabel `anggota_rombel`.

---

## 4. Panduan Restore Data Siswa di VPS

File pemulihan SQL sudah diekstrak dan disiapkan secara otomatis dari file backup valid (`Auto_Full_20261012_130712.sql`):
📄 **`docs/restore_siswa_hilang.sql`**

File ini berisi query pemulihan lengkap untuk:
* 11 akun login siswa di tabel `users` (User ID: 51, 95, 230, 240, 274, 318, 351, 359, 451, 455, 475)
* 25 data siswa lengkap di tabel `siswa` (Biodata, NIS, NISN, NIK, tanggal lahir, nama ortu, dsb.)

---

### Langkah-langkah Eksekusi di VPS:

#### Langkah 1: Login SSH ke Server VPS
```bash
ssh user@ip_vps_anda
```

#### Langkah 2: Masuk ke Direktori Proyek
```bash
cd /path/to/erapoteasy
```
*(Contoh umum: `cd /var/www/erapot` atau `cd /home/user/htdocs/erapoteasy`)*

#### Langkah 3: Tarik File Restore Terbaru dari Git Repository
```bash
git pull origin main
```
Pastikan file `docs/restore_siswa_hilang.sql` sudah tersedia di folder `docs/`.

#### Langkah 4: Buat Backup Pengaman Database VPS (PENTING!)
Sebelum melakukan eksekusi, buat backup darurat terlebih dahulu:
```bash
mysqldump -u [db_user] -p [db_name] > backup_sebelum_restore_$(date +%Y%m%d_%H%M%S).sql
```
*(Ganti `[db_user]` dan `[db_name]` sesuai konfigurasi `.env`)*

#### Langkah 5: Eksekusi File Restore SQL
Jalankan file SQL restore langsung ke database MySQL:
```bash
mysql -u [db_user] -p [db_name] < docs/restore_siswa_hilang.sql
```
Masukkan password database saat diminta.

> **Catatan Penanganan Error #1062 (Duplicate entry):**
> File `docs/restore_siswa_hilang.sql` sudah diperbarui menggunakan sintaks **`INSERT IGNORE INTO`**. Hal ini mencegah error `#1062` jika ada siswa (seperti Dion Arkaan Alkhalifi / Luthfi Alfahrezi Aurelio) yang sebelumnya sudah sempat diinput ulang manual ke database dengan ID baru. Baris yang sudah ada akan otomatis di-skip dan baris yang hilang akan berhasil dipulihkan.

> **Alternatif via phpMyAdmin (Jika Ada):**
> 1. Buka phpMyAdmin di VPS / CPanel.
> 2. Pilih database rapor (`raporsmpit`).
> 3. Buka tab **Import**.
> 4. Pilih file `docs/restore_siswa_hilang.sql` lalu klik **Go / Kirim**.

---

### Langkah 6: Pembersihan 40 Data Siswa Rusak (ID 581 - 620)

Setelah data asli berhasil dipulihkan, 40 record siswa hasil import yang kolomnya tergeser (dan akun login dummy-nya) perlu dibersihkan agar tidak menjadi data sampah.

Jalankan perintah SQL berikut di MySQL VPS / phpMyAdmin:
```sql
-- Nonaktifkan pengecekan foreign key sementara
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Hapus record orangtua_wali yang terkait dengan siswa rusak (ID 581 s/d 620)
DELETE FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620;

-- 2. Hapus akun users yang terkait dengan siswa rusak
DELETE FROM users WHERE id IN (
    SELECT user_id FROM siswa WHERE id BETWEEN 581 AND 620 AND user_id IS NOT NULL
);

-- 3. Hapus data siswa rusak dari tabel siswa
DELETE FROM siswa WHERE id BETWEEN 581 AND 620;

-- Aktifkan kembali pengecekan foreign key
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 5. Verifikasi Setelah Restore

Setelah eksekusi di VPS selesai, lakukan verifikasi:

1. **Cek Jumlah Siswa di Database**:
   ```sql
   -- Cek siswa di kelas Amazonite (Harus kembali 26 siswa)
   SELECT COUNT(*) FROM siswa WHERE rombel_id = 77 AND status_siswa = 'Aktif';

   -- Cek 3 siswa yang sebelumnya hilang
   SELECT id, nama_lengkap, rombel_id FROM siswa WHERE id IN (536, 538, 548);
   ```

2. **Cek Melalui Aplikasi Web**:
   * Login sebagai wali kelas **Amazonite** (Ustadz Adhitya Prayoga) -> Masuk menu **Daftar Siswa**.
   * Pastikan total siswa kembali menjadi **26 orang** dan nama *Azka Adhyastha*, *Bima Adi Brata*, dan *Muhammad Fadhlan Idris* sudah muncul kembali di daftar.
   * Cek juga akun wali kelas **Carnelian**, **Zamrud**, **Ruby**, dan **Kyanite**.

---

## 6. Tindakan Pencegahan Jangka Panjang (Mitigasi)

1. **Perbaikan `SiswaController::delete()`**:
   Tambahkan penghapusan relasi `anggota_rombel` saat siswa dihapus agar tidak meninggalkan orphan data:
   ```php
   $db->table('anggota_rombel')->where('siswa_id', $id)->delete();
   ```
2. **Validasi Header Import Lebih Ketat**:
   Gunakan deteksi nama kolom header yang wajib cocok 100% sebelum memulai iterasi baris data Excel untuk mencegah insiden kolom tergeser terulang kembali.
