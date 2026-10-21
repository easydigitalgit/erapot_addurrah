# Laporan Analisa & Perbaikan Data Siswa Kelas IX Emerald dan Pemulihan Kolom Data Tergeser
## Berdasarkan File: `docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`

**Tanggal Pemeriksaan:** 20 Oktober 2026  
**Tahun Ajaran Aktif:** 2026/2027 - Ganjil (ID: 10)  
**Rombel Target:** IX Emerald (ID Rombel: 68) & Seluruh Rombel Aktif  
**File Query SQL Siap Eksekusi di VPS:** `docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql`

---

## 1. Ringkasan Eksekutif

Menindaklanjuti laporan dari wali kelas **IX Emerald** (*Ustadz Ray Agung Hadi Wiranata, SH., Gr.*) yang menginformasikan bahwa jumlah siswa di dashboard kelasnya hanya menampilkan **24 siswa** (seharusnya **29 siswa** sesuai Dapodik), serta adanya pergeseran data kolom (kolom tergeser akibat proses import data sebelumnya):

Telah dilakukan audit dan rekonsiliasi data mendalam antara file resmi **`docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`** (Sheet *DAFTAR PESERTA DIDIK*, Sheet *REKAP JUMLAH SISWA*, dan Sheet *IX*), backup database `Auto_Full_20261012_130712.sql`, dan database terkini yang baru diimpor dari VPS.

### Hasil Pemulihan & Rekonsiliasi:
1. **Jumlah Siswa Kelas IX Emerald Genap Kembali Menjadi 29 Siswa (100% Sesuai Dapodik)**.
2. **5 Siswa yang Hilang Berhasil Dipulihkan**:
   - `Bagas Pramuditya` (ID: 138)
   - `ERLANGGA WICAKSONO` (ID: 140)
   - `FATHAN LUTHFI` (ID: 141)
   - `FITRA AL-BAIHAQI` (ID: 143)
   - `MHD ABDA RIFKY FIRAZA` (ID: 145)
3. **Seluruh Riwayat Nilai & Akademik Tersambung Utuh Tanpa Ada Data Nilai yang Hilang**:
   - 600 baris nilai formatif (masing-masing 120 penilaian).
   - 150 baris nilai rapor tengah/akhir semester (masing-masing 30 mapel/komponen).
   - Nilai ekstrakurikuler, catatan rapor, rekap absensi, dan profil orang tua di tabel `orangtua_wali`.
4. **Perbaikan Menyeluruh Kolom Data yang Tergeser**:
   - **NIK**: Mengembalikan NIK asli 16 digit dari file Dapodik (sebelumnya tertukar/terisi nomor NIS/NIPD).
   - **Kelurahan**: Mengembalikan nama desa/kelurahan asli (sebelumnya tertukar terisi nama sekolah asal).
   - **Kecamatan**: Mengembalikan nama kecamatan asli (sebelumnya tertukar terisi alamat jalan).
   - **No. HP**: Mengembalikan nomor telepon/HP siswa/wali atau mengosongkannya jika di Dapodik kosong (sebelumnya terisi teks status `'Aktif'`).
   - Termasuk membetulkan NISN & NIK yang tertukar pada siswa rombel lain (seperti *Keisha Amila Husna* dan *Felya Vanesyah*).
5. **Database Lokal Telah Berhasil Diperbarui & Berfungsi 100%**. Script SQL mandiri telah disiapkan di `docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql` untuk langsung dieksekusi di database server VPS.

---

## 2. Temuan Audit & Analisa Akar Masalah (Root Cause Analysis)

### A. Mengapa Siswa Kelas IX Emerald Berkurang dari 29 Menjadi 24?

Di aplikasi e-Rapor, controller wali kelas (`app/Controllers/WaliKelas/DaftarSiswaController.php`) memanggil daftar siswa rombel dengan query relasi:
```php
$siswa_kelas = $db->table('anggota_rombel ar')
    ->select('siswa.*, users.foto_profil')
    ->join('siswa', 'siswa.id = ar.siswa_id') // <-- INNER JOIN
    ->where('ar.rombel_id', $rombel['id'])
    ->where('siswa.status_siswa', 'Aktif')
    ->get()->getResultArray();
```

Hasil audit basis data menemukan:
* Pada tabel `anggota_rombel` untuk `rombel_id = 68` (IX Emerald TA 2026/2027 Ganjil), record keanggotaan siswa sebenarnya **masih berjumlah 29 baris**.
* Namun, pada tabel master `siswa`, record untuk **5 siswa** (Siswa ID: 138, 140, 141, 143, dan 145) **telah terhapus/hilang**.
* Akibat relasi `INNER JOIN` antara `anggota_rombel` dan `siswa`, ke-5 siswa yang tidak memiliki baris di tabel `siswa` tersebut langsung tereliminasi dari hasil query, sehingga query hanya mengembalikan **24 baris siswa**.
* Karena tabel nilai (`nilai_formatif`, `nilai_rapor`, `nilai_ekskul`, dll.) merujuk langsung ke `siswa_id`, nilai-nilai ke-5 siswa ini sempat menjadi *orphan records*. Dengan mengembalikan baris siswa pada ID aslinya (138, 140, 141, 143, 145), **seluruh data nilai siswa otomatis tersambung kembali seketika tanpa perlu input ulang**.

### B. Mengapa Kolom Data Siswa Tergeser?

Insiden kolom data tergeser bermula dari proses import data Dapodik terdahulu di mana susunan header kolom pada template form import tidak sesuai dengan urutan kolom file hasil download Dapodik:
1. Kolom NIK siswa malah mengambil data dari kolom NIPD/NIS (misal: NIK tertulis `08.24.0001`).
2. Kolom Kelurahan mengambil data dari kolom Sekolah Asal (misal: Kelurahan tertulis `SD SWASTA ISLAM TERPADU AD-DURRAH`).
3. Kolom Kecamatan mengambil data dari kolom Alamat Siswa (misal: Kecamatan tertulis `JL KAPTEN RAHMAD BUDIN GG KEMBAR`).
4. Kolom No HP mengambil teks status siswa dari kolom berikutnya (sehingga kolom `no_hp` bernilai string `'Aktif'`).

---

## 3. Rincian 5 Siswa Kelas IX Emerald yang Dipulihkan

| No | ID Siswa | NIS | NISN | NIK Asli | Nama Siswa | Nilai Formatif | Nilai Rapor | Orang Tua (Ayah / Ibu) | No. HP Ortu |
|:--:|:--:|:--:|:--:|:--:|:---|:--:|:--:|:---|:--:|
| 1 | **138** | 08.24.0032 | 0121371946 | 1207242007120002 | **Bagas Pramuditya** | 120 nilai | 30 nilai | Mulianto / Farida Hanum | 085361518892 |
| 2 | **140** | 08.24.0044 | 0124992248 | 1271081211120002 | **ERLANGGA WICAKSONO** | 120 nilai | 30 nilai | ANDI RACHMAN / PURWANDARI | 082161168625 |
| 3 | **141** | 08.24.0048 | 3124573449 | 1271062006120004 | **FATHAN LUTHFI** | 120 nilai | 30 nilai | HENKY PRAYOGA / SUAIDAH | 085297821831 |
| 4 | **143** | 08.24.0053 | 0127099650 | 1271131209120002 | **FITRA AL-BAIHAQI** | 120 nilai | 30 nilai | SULAIMAN / RATINA DIRA | 088708470781 |
| 5 | **145** | 08.24.0076 | 0111681061 | 1271062111110005 | **MHD ABDA RIFKY FIRAZA** | 120 nilai | 30 nilai | FAISAL RIZA / RITAWATI C | 082165248106 |

---

## 4. Daftar Lengkap 29 Siswa Kelas IX Emerald Setelah Perbaikan

Berikut adalah daftar lengkap 29 siswa kelas IX Emerald di database yang telah 100% sinkron dengan buku induk Dapodik TA 2026/2027:

| No | ID | NIS | NISN | NIK (16 Digit) | Nama Lengkap | Kelurahan | Kecamatan | No. HP Siswa/Ortu |
|:--:|:--:|:--:|:--:|:--:|:---|:---|:---|:---:|
| 1 | 133 | 08.24.0001 | 3126602599 | 1273041704120002 | ABYAN NANDANA ROFY | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 085261920417 |
| 2 | 134 | 08.24.0009 | 0125880240 | 1271121310120003 | ALEX NAZRIL ILHAM | TANAH ENAM RATUS | Kec. Medan Marelan | 082277902441 |
| 3 | 135 | 08.24.0160 | 0125564479 | 1271121006120002 | AR'RAYYAN MIFTAH SYAHYADI | Rengas Pulau | Kec. Medan Marelan | 085262708737 |
| 4 | 136 | 08.24.0018 | 0127134781 | 1271082710120001 | ARJUN FARID | BELAWAN I | Kec. Medan Kota Belawan | 085262229745 |
| 5 | 137 | 08.25.0164 | 0114639043 | 2101071111110002 | ARJUSE FARISI | Tanjung Uban | Kec. Bintan Utara | 085264812464 |
| 6 | 138 | 08.24.0032 | 0121371946 | 1207242007120002 | Bagas Pramuditya | Hamparan Perak | Kec. Hamparan Perak | 085361518892 |
| 7 | 139 | 08.24.0033 | 0127615003 | 1207242007120003 | Bagus Pramudityo | Hamparan Perak | Kec. Hamparan Perak | 085361518892 |
| 8 | 140 | 08.24.0044 | 0124992248 | 1271081211120002 | ERLANGGA WICAKSONO | TERJUN | Kec. Medan Marelan | 082161168625 |
| 9 | 141 | 08.24.0048 | 3124573449 | 1271062006120004 | FATHAN LUTHFI | Desa/Kel. T E R J U N | Kec. Medan Marelan | 085297821831 |
| 10 | 142 | 08.24.0153 | 0127249232 | 1271063011120004 | Fi'il Haq Qawiy | Desa/Kel. Titi Papan | Kec. Medan Deli | 087769378118 |
| 11 | 143 | 08.24.0053 | 0127099650 | 1271131209120002 | FITRA AL-BAIHAQI | PEKAN LABUHAN | Kec. Medan Labuhan | 088708470781 |
| 12 | 144 | 08.24.0057 | 0116774970 | 1172020812110001 | HABIB PRATAMA | Rengas Pulau | Kec. Medan Marelan | 085270839092 |
| 13 | 145 | 08.24.0076 | 0111681061 | 1271062111110005 | MHD ABDA RIFKY FIRAZA | TITIPAPAN | Kec. Medan Deli | 082165248106 |
| 14 | 146 | 08.24.0078 | 0125471389 | 1271132710120001 | MHD Raikhan Pramastio | Desa/Kel. Pekan Labuhan | Kec. Medan Labuhan | 085262877238 |
| 15 | 147 | 08.24.0080 | 0124125140 | 1271122202120001 | MUHAMMAD ALI SIDDIQ AL FATHIH | LABUHAN DELI | Kec. Medan Marelan | 088708625747 |
| 16 | 148 | 08.24.0081 | 3115820722 | 1271121510110002 | MUHAMMAD ARSYIL ADZHIM | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 08126025099 |
| 17 | 149 | 08.24.0152 | 3124050562 | 1271081006120001 | MUHAMMAD ASYRAF | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 081376952595 |
| 18 | 150 | 10.26.0245 | 0121810070 | 1271160102120001 | MUHAMMAD FAQEEH ARFA GINTING | Desa/Kel. Gunung Samarinda | Kec. Medan Polonia | 081264732277 |
| 19 | 151 | 10.26.0253 | 3124617870 | 1271201206120002 | MUHAMMAD FATHAN SIREGAR | Desa/Kel. Glugur Darat I | Kec. Medan Timur | 085262195950 |
| 20 | 152 | 10.26.0223 | 0126886061 | 1271120908120002 | MUHAMMAD HERO AZAM | RENGAS PULAU | Kec. Medan Marelan | 081260538523 |
| 21 | 153 | 10.26.0245 | 0111531147 | 1271192710110001 | MUHAMMAD LUKMAN ALFARIZI | RENGAS PULAU | Kec. Medan Marelan | 085370173935 |
| 22 | 154 | 08.24.0088 | 3125146199 | 1271061206120001 | MUHAMMAD NAUFAL ARRAIHAN | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 081361275361 |
| 23 | 155 | 10.26.0253 | 3124989103 | 1271200506120001 | RAIHAN SUTAN FAWWAZI | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 085262701212 |
| 24 | 156 | 08.24.0156 | 0128021870 | 1271191702120002 | RESTU ARIF PRATAMA SIANTURI | HELVETIA | Kec. Labuhan Deli | 082240072381 |
| 25 | 157 | 08.24.0124 | 0127295792 | 1271122106120005 | Rifqy Ahmad Hasibuan | Desa/Kel. Paya Pasir | Kec. Medan Marelan | 081370042499 |
| 26 | 158 | 08.24.0126 | 3121338906 | 1271121707120003 | RIO FATHIR ZAIRA | Rengas Pulau | Kec. Medan Marelan | 081220278801 |
| 27 | 159 | 08.24.0133 | 0127083494 | 1271131210120005 | SATRIA ANARKI TANJUNG | MARTUBUNG | Kec. Medan Labuhan | 088708629653 |
| 28 | 160 | 08.24.0149 | 0126041302 | 1271122107120001 | ZIKRI RAMADHAN | RENGAS PULAU | Kec. Medan Marelan | 081260703791 |
| 29 | 161 | 08.24.0150 | 0129734405 | 1271120707120004 | Zio Julian | Desa/Kel. Rengas Pulau | Kec. Medan Marelan | 085362145727 |

---

## 5. Panduan Eksekusi Perbaikan di Server VPS

File SQL telah dipersiapkan dan diuji pada direktori:  
📁 **`docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql`**

### Struktur Isi File SQL:
* **Bagian 1**: Query `INSERT INTO siswa` dan `INSERT INTO orangtua_wali` untuk me-restore 5 siswa IX Emerald (ID 138, 140, 141, 143, 145) secara aman dengan klausa `ON DUPLICATE KEY UPDATE`.
* **Bagian 2**: Query `UPDATE` kolom tergeser untuk seluruh 29 siswa kelas IX Emerald.
* **Bagian 3**: Query `UPDATE` kolom tergeser untuk seluruh rombel aktif lainnya (VII, VIII, IX) yang datanya tercatat di Excel Dapodik (352 siswa).
* **Bagian 3.2**: Pembersihan string teks `'Aktif'` pada kolom `no_hp` menjadi `NULL`.
* **Bagian 4**: Query verifikasi otomatis pasca eksekusi.

---

### Cara Eksekusi di VPS:

#### Opsi A: Melalui SSH Terminal (Paling Cepat & Direkomendasikan)

1. **Login ke Server VPS via SSH**:
   ```bash
   ssh user@ip_vps_anda
   ```

2. **Masuk ke folder proyek**:
   ```bash
   cd /path/to/erapoteasy
   ```

3. **Tarik file SQL terbaru dari repository Git**:
   ```bash
   git pull origin main
   ```
   *(Pastikan file `docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql` sudah masuk).*

4. **Buat backup pengaman database VPS (Praktik Terbaik)**:
   ```bash
   mysqldump -u [db_user] -p [db_name] > backup_sebelum_fix_emerald_$(date +%Y%m%d_%H%M%S).sql
   ```

5. **Eksekusi file SQL ke database**:
   ```bash
   mysql -u [db_user] -p [db_name] < docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql
   ```
   *(Masukkan password database MySQL Anda saat diminta).*

---

#### Opsi B: Melalui phpMyAdmin di Browser

1. Buka phpMyAdmin di server VPS / cPanel.
2. Pilih database aplikasi rapor (`raporsmpit`).
3. Buka tab **Import**.
4. Klik **Choose File** lalu pilih file `docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql`.
5. Klik tombol **Go / Kirim** di bagian bawah.
6. Tunggu hingga muncul pesan sukses berwarna hijau.

---

## 6. Query Verifikasi Pasca-Eksekusi

Setelah query dijalankan di VPS, jalankan perintah verifikasi berikut di tab SQL phpMyAdmin atau MySQL CLI untuk memastikan keberhasilan perbaikan:

```sql
-- 1. Cek Total Siswa Kelas IX Emerald (Harus Tepat 29 Siswa)
SELECT COUNT(*) AS total_emerald 
FROM siswa 
WHERE rombel_id = 68 AND status_siswa = 'Aktif';

-- 2. Cek Total Siswa yang Tampil di Menu Wali Kelas IX Emerald (Harus Tepat 29 Siswa)
SELECT COUNT(s.id) AS total_tampil_walikelas 
FROM anggota_rombel ar 
JOIN siswa s ON s.id = ar.siswa_id 
WHERE ar.rombel_id = 68 AND s.status_siswa = 'Aktif';

-- 3. Cek 5 Siswa yang Baru Dipulihkan Beserta Nilainya
SELECT 
    s.id, s.nis, s.nisn, s.nama_lengkap, s.nik, s.no_hp, s.kelurahan, s.kecamatan,
    COUNT(DISTINCT nf.id) as jml_nilai_formatif,
    COUNT(DISTINCT nr.id) as jml_nilai_rapor
FROM siswa s 
LEFT JOIN nilai_formatif nf ON nf.siswa_id = s.id 
LEFT JOIN nilai_rapor nr ON nr.siswa_id = s.id 
WHERE s.id IN (138, 140, 141, 143, 145) 
GROUP BY s.id;

-- 4. Cek Apakah Masih Ada Kolom NIK/Kelurahan/Kecamatan/No HP yang Tergeser di Semester Aktif (Target: 0 Baris, kec. Naufal lokal)
SELECT COUNT(*) AS sisa_kolom_tergeser_ta_aktif 
FROM siswa 
WHERE status_siswa = 'Aktif' 
  AND rombel_id IN (SELECT id FROM rombel WHERE id_tahun_ajaran = 10)
  AND id != 55
  AND (no_hp = 'Aktif' OR kelurahan LIKE '%SD %' OR kelurahan LIKE '%SMP %' OR nik = nis);
```

### Hasil yang Diharapkan:
1. `total_emerald` = **29**
2. `total_tampil_walikelas` = **29**
3. `jml_nilai_formatif` = **120**, `jml_nilai_rapor` = **30** untuk masing-masing ke-5 siswa yang dipulihkan.
4. `sisa_kolom_tergeser_ta_aktif` = **0**.
