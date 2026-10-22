# LAPORAN ANALISIS & PERBAIKAN DATA SISWA TERGESER (ID 581 - 620)

**Tanggal Analisis & Eksekusi:** 21 Oktober 2026  
**Target:** Tabel `siswa`, `orangtua_wali`, dan `users` (Range ID 581 s/d 620)  
**Status:** Selesai (Dianalisis, Dibersihkan di Lokal, & Disiapkan Query untuk VPS)

---

## 1. Ringkasan Kasus
Ditemukan inkonsistensi dan keanehan data parah pada tabel master `siswa` untuk rentang ID **581 sampai dengan 620** (total 40 baris):
1. Data pada ID 581 berisi teks header Excel:
   - `nis`: `No`
   - `nisn`: `Nama`
   - `nama_lengkap`: `JK`
   - `agama`: `NIK`
   - `diterima_dikelas`: `Kode Pos`
2. Data pada ID 582 s/d 620 mengalami **pergeseran kolom massal (column shifting)**:
   - Kolom `nis` terisi **Nomor Urut** (`1`, `2`, `3`, `26`, `27`, `144`, dst.)
   - Kolom `nisn` terisi **Nama Lengkap Siswa** (`LUTFY SAKHI ZAYDAN`, `ABYAN NANDANA ROFY`, `FATIH SEPTIAN`, dst.)
   - Kolom `nama_lengkap` terisi **Jenis Kelamin** (`L`, `P`)
   - Kolom `agama` terisi **NIK** Siswa (`1207241306140004`, `1271202107140002`, dst.)
   - Kolom `diterima_dikelas` terisi **Kode Pos** (`20254`, `20255`, `20374`, dst.)
   - Kolom `alamat_siswa`, `kelurahan`, `kecamatan` tertukar dan tidak sesuai.

---

## 2. Akar Masalah (Root Cause)

Pergeseran data ini berasal dari proses **Import Data Siswa Excel** terdahulu (tercatat `tgl_diterima`: `2026-10-12`). Faktor penyebabnya adalah:

### A. Ketiadaan Deteksi Baris Header Dinamis pada Controller Lama
Pada kode lama [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php) sebelum diperbaiki:
- Sistem mengasumsikan baris ke-1 selalu merupakan header standar, dan baris data langsung dimulai dari baris ke-2 tanpa validasi apakah baris tersebut benar-benar data siswa.
- Baris header bertuliskan `No | Nama | JK | NIK | ...` akhirnya ikut terproses dan tersimpan sebagai "Siswa Baru" dengan ID 581.

### B. Fallback Kolom Hardcoded (`A`, `B`, `C`, `D`, `E`, ...)
Sistem import versi lama memiliki fallback kolom kaku:
- Kolom `A` diasumsikan sebagai `NIS`
- Kolom `B` diasumsikan sebagai `NISN`
- Kolom `C` diasumsikan sebagai `NIK`
- Kolom `D` diasumsikan sebagai `Nama Lengkap`
- Kolom `E` diasumsikan sebagai `Jenis Kelamin`

Namun, file Excel yang diunggah memiliki susunan kolom yang berbeda:
`[A] No | [B] Nama Lengkap | [C] Jenis Kelamin | [D] NIK | [E] Tempat Lahir | [F] Tanggal Lahir | [G] Alamat | [H] Kelurahan | [I] Kecamatan | [J] Kode Pos`

Akibatnya terjadi pergeseran berantai ke kiri/kanan:
| Kolom di Database | Data yang Masuk | Seharusnya Berisi |
|---|---|---|
| `nis` | Nomor urut Excel (1, 2, 3...) | Nomor Induk Siswa |
| `nisn` | Nama lengkap siswa | NISN (10 digit) |
| `nama_lengkap` | Inisial gender (L / P) | Nama lengkap siswa |
| `agama` | NIK (16 digit) | Islam / Kristen / dll. |
| `diterima_dikelas`| Kode pos (20254, dll.) | Kelas saat diterima |

### C. Pembuatan Otomatis Akun `users` yang Korup
Karena `nis` terbaca sebagai `No`, `1`, `2`, `3`..., sistem import lama otomatis membuat akun login di tabel `users` dengan username `No`, `1`, `2`..., serta akun orang tua dengan username `HP` (karena header HP dibaca sebagai nomor telepon).

---

## 3. Hasil Audit Relasi Database

Dilakukan pemindaian relasi data untuk ID 581 s/d 620 ke seluruh tabel di database:
1. **Relasi Akademik / Nilai / Rombel:**
   - `anggota_rombel`: **0 baris** (tidak terdaftar di rombel manapun)
   - `nilai_formatif`: **0 baris**
   - `nilai_sumatif`: **0 baris**
   - `nilai_rapor`: **0 baris**
   - `absensi_harian` / `rekap_absensi`: **0 baris**
2. **Duplikasi Terhadap Siswa Asli:**
   - Hampir seluruh siswa pada baris korup ini **sudah memiliki data resmi yang valid** dengan ID lain (misalnya Lutfy Sakhi Zaydan di ID 508, Abyan Nandana Rofy di ID 133, Fatih Septian di ID 502, Zayn Aliando di ID 521, dll.).
   - 4 siswa yang tidak terdaftar saat itu (Arjuse Farisi, Athallah Alsulthan, Malaika Zahfira, Rifki Zulkarnain) adalah siswa Dapodik yang sebelumnya orphan dan telah dibuatkan alur pemulihan.
3. **Kesimpulan:** 40 record pada rentang ID 581 s/d 620 adalah **data sampah murni (corrupted orphan duplicates)** yang mengotori database.

---

## 4. Tindakan Perbaikan yang Telah Dilakukan

### A. Pembersihan Data Lokal (Berhasil 100%)
1. Menghapus 40 baris data dari `orangtua_wali` (`WHERE siswa_id BETWEEN 581 AND 620`).
2. Menghapus 75 akun login sampah dari tabel `users` (username `No`, `1`, `2`..., `HP`, `W54`...) dengan pengecualian user yang digunakan oleh siswa/wali yang sah.
3. Menghapus 40 baris siswa korup dari tabel `siswa` (`WHERE id BETWEEN 581 AND 620`).

### B. Proteksi Kode di Controller (Pencegahan Permanen)
Di [SiswaController.php](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/SiswaController.php):
- **Deteksi Header Dinamis:** Menguji baris 1 s/d 10 secara otomatis untuk mendeteksi posisi baris header berdasarkan kata kunci terbanyak. Baris kop sekolah dan baris judul di atasnya otomatis diabaikan.
- **Mapping Kolom Cerdas:** Kolom dipetakan berdasarkan pencocokan nama header (seperti `nama`, `nis`, `nisn`, `nik`, `kelamin`, `alamat`, `kelurahan`, `kecamatan`), bukan berdasarkan indeks kolom kaku.
- **Proteksi Kata Kunci:** Menghilangkan false positive antara `sisWA` dengan `wa` (WhatsApp) serta `diterima di kelas` dengan `kelas`.

---

## 5. Query SQL untuk Perbaikan di VPS

Jalankan query SQL berikut di database VPS (via **phpMyAdmin** atau **MySQL CLI**) untuk membersihkan 40 data korup tersebut:

```sql
START TRANSACTION;

-- 1. Hapus akun pengguna (users) sampah yang tercipta dari import korup
-- (Hanya menghapus akun yang tidak dipakai oleh siswa/orang tua sah lainnya)
DELETE FROM users 
WHERE id IN (
    SELECT user_id FROM (
        SELECT user_id FROM siswa WHERE id BETWEEN 581 AND 620 AND user_id IS NOT NULL
        UNION
        SELECT user_id FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620 AND user_id IS NOT NULL
    ) AS tmp_users
)
AND id NOT IN (
    SELECT user_id FROM siswa WHERE (id NOT BETWEEN 581 AND 620) AND user_id IS NOT NULL
)
AND id NOT IN (
    SELECT user_id FROM orangtua_wali WHERE (siswa_id NOT BETWEEN 581 AND 620 OR siswa_id IS NULL) AND user_id IS NOT NULL
);

-- 2. Hapus data orang tua / wali korup yang terkait ID 581 - 620
DELETE FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620;

-- 3. Hapus data siswa korup ID 581 - 620
DELETE FROM siswa WHERE id BETWEEN 581 AND 620;

COMMIT;
```
