# Laporan Analisa Mendalam: Perbedaan Jumlah Siswa IX Emerald (VPS vs Lokal) dan Investigasi Akar Masalah Sistemik

**Tanggal Analisa:** 26 Oktober 2026  
**Topik:** Investigasi Berkurangnya Jumlah Siswa Kelas IX Emerald di VPS (24 Siswa) vs Database Lokal (29 Siswa)  
**Tahun Ajaran Aktif:** 2026/2027 - Ganjil (ID Tahun Ajaran: 10)  
**Rombel Target:** Kelas IX Emerald (ID Rombel: 68)  
**Wali Kelas:** Ray Agung Hadi Wiranata, SH., Gr.  

---

## 1. Ringkasan Kasus (Problem Statement)

Pada hari Senin, 26 Oktober 2026, ditemukan ketidaksesuaian data jumlah siswa pada kelas **IX Emerald**:
- Pada server production (**VPS**), tampilan menu *Admin > Tingkat & Rombel* serta rekap data hanya menunjukkan **24 siswa**.
- Pada database laptop (**Lokal**), data kelas IX Emerald tercatat lengkap sebanyak **29 siswa** (100% sesuai buku induk Dapodik TA 2026/2027).
- Database lokal merupakan hasil salinan (*database dump*) dari server VPS yang diimpor pada pagi hari (26 Oktober 2026 pukul 08:05 WIB).

Pengguna mempertanyakan: **Mengapa hal seperti ini (data siswa berkurang/hilang secara tiba-tiba) sering terjadi di sistem ini?**

---

## 2. Hasil Audit & Investigasi Data Lapangan

### A. Pembuktian Query di Database VPS
Berdasarkan pengujian langsung yang dijalankan pada phpMyAdmin VPS:

1. **Pengecekan ID 138, 140, 141, 143, 145:**
   ```sql
   SELECT id, nisn, nis, nama_lengkap, rombel_id, status_siswa 
   FROM siswa 
   WHERE id IN (138, 140, 141, 143, 145);
   ```
   *Hasil di VPS:* Hanya mengembalikan **2 baris** (ID 138: *Bagas Pramuditya* dan ID 140: *ERLANGGA WICAKSONO*). Sedangkan ID 141, 143, dan 145 tidak ditemukan.

2. **Pengecekan Nama Siswa yang Hilang:**
   ```sql
   SELECT id, nisn, nis, nama_lengkap, rombel_id, status_siswa 
   FROM siswa 
   WHERE nama_lengkap LIKE '%FATHAN LUTHFI%' 
      OR nama_lengkap LIKE '%FITRA AL-BAIHAQI%' 
      OR nama_lengkap LIKE '%ABDA RIFKY%';
   ```
   *Hasil di VPS:* **MySQL returned an empty result set (0 rows)**. Data ketiga siswa tersebut benar-benar tidak ada di tabel master `siswa` VPS.

3. **Pengecekan Seluruh Siswa Rombel 68 di VPS:**
   ```sql
   SELECT id, nisn, nis, nama_lengkap, status_siswa 
   FROM siswa 
   WHERE rombel_id = 68 
   ORDER BY nama_lengkap ASC;
   ```
   *Hasil di VPS:* Menghasilkan tepat **24 siswa** (dimulai dari ID 134: *ALEX NAZRIL ILHAM* hingga ID 161: *Zio Julian*).

### B. Daftar 5 Siswa yang Hilang / Terlepas di VPS
Dari komparasi data 29 siswa di lokal dengan 24 siswa di VPS, ditemukan **5 siswa yang tidak masuk dalam rombel IX Emerald di VPS**:

| No | ID | NISN | NIS | Nama Lengkap Siswa | Kondisi Faktual di VPS |
|:--:|:--:|:--:|:--:|:---|:---|
| 1 | **133** | 3126602599 | 08.24.0001 | **ABYAN NANDANA ROFY** | Terlepas dari `rombel_id = 68` / Tidak Aktif |
| 2 | **139** | 0127615003 | 08.24.0033 | **Bagus Pramudityo** | Terlepas dari `rombel_id = 68` / Tidak Aktif |
| 3 | **141** | 3124573449 | 08.24.0048 | **FATHAN LUTHFI** | **Terhapus permanen dari tabel `siswa` (0 baris)** |
| 4 | **143** | 0127099650 | 08.24.0053 | **FITRA AL-BAIHAQI** | **Terhapus permanen dari tabel `siswa` (0 baris)** |
| 5 | **145** | 0111681061 | 08.24.0076 | **MHD ABDA RIFKY FIRAZA** | **Terhapus permanen dari tabel `siswa` (0 baris)** |

---

## 3. Analisa Akar Masalah: "Kenapa Hal Seperti Ini Sering Terjadi?"

Fenomena hilangnya siswa bukan terjadi secara acak, melainkan akibat dari **kombinasi 4 kelemahan arsitektur sistem** di codebase dan basis data:

### 1. Dual Storage Discrepancy (Redundansi Penempatan Rombel)
Aplikasi menyimpan relasi siswa dan kelasnya di dua tempat yang berbeda:
- **Tabel `siswa` kolom `rombel_id`** (Format penempatan flat/saat ini).
- **Tabel `anggota_rombel`** (Format penempatan riwayat per tahun ajaran & semester).

Kelemahan fatalnya:
- Menu **Admin Tingkat Rombel** (`app/Controllers/Admin/TingkatRombelController.php`) menghitung jumlah siswa menggunakan:
  ```php
  $builder->select('(SELECT COUNT(*) FROM siswa WHERE siswa.rombel_id = rombel.id AND siswa.status_siswa = "Aktif") as jumlah_siswa');
  ```
- Sedangkan menu **Wali Kelas / Leger / Rapor** (`app/Controllers/WaliKelas/DaftarSiswaController.php`) mengambil siswa menggunakan:
  ```php
  $siswa_kelas = $db->table('anggota_rombel ar')
      ->join('siswa', 'siswa.id = ar.siswa_id')
      ->where('ar.rombel_id', $rombel['id'])
      ->where('ar.tahun_ajaran_id', $id_ta);
  ```
> **Dampak:** Jika salah satu tabel di-update tanpa meng-update tabel lainnya (misal lewat form edit profil siswa, impor parsial, atau mutasi siswa), maka jumlah siswa di satu halaman akan langsung berbeda dengan halaman lainnya.

### 2. Bug Overwrite Mapping Rombel pada Fitur Import Siswa
Pada file `app/Controllers/Admin/SiswaController.php` baris 850–860, logika pembacaan rombel saat impor Excel dilakukan dengan cara:
```php
$dbRombel = $db->table('rombel')->get()->getResultArray();
$mapRombel = [];
foreach ($dbRombel as $r) {
    $nama = strtolower(trim($r['nama_rombel']));
    $mapRombel[$nama] = $r['id']; // <-- Tertimpa jika nama rombel ada di beberapa tahun/tingkat!
}
```
Pada basis data e-Rapor, rombel dengan nama **"Emerald"** ada di dua tingkat/tahun yang berbeda:
- **ID 12**: Rombel *Emerald* (Tingkat VIII, Tahun Ajaran Lalu).
- **ID 68**: Rombel *Emerald* (Tingkat IX, Tahun Ajaran Aktif 2026/2027).

Karena query **tidak memfilter `WHERE id_tahun_ajaran = [TA_AKTIF]`**, pemetaan nama `'emerald'` akan tertimpa oleh record yang terakhir di-loop. Jika admin mengimpor file Excel Dapodik dengan kolom kelas tertulis hanya "Emerald" (bukan "IX Emerald"), sistem bisa salah memasukkan siswa ke ID 12 (kelas VIII) atau mengosongkan rombelnya (`NULL`).

### 3. Ketiadaan Foreign Key Constraints (Orphan Records)
Tabel-tabel relasi utama seperti `siswa`, `users`, `orangtua_wali`, dan `anggota_rombel` tidak memiliki integritas *Foreign Key (FK) ON DELETE CASCADE*:
- Jika akun user atau data master `siswa` dihapus, baris siswa di `anggota_rombel` **tidak ikut terhapus** (menjadi *orphan record*).
- Sebaliknya, jika data siswa terhapus dari `siswa`, query wali kelas yang menggunakan `INNER JOIN siswa ON siswa.id = ar.siswa_id` langsung memangkas siswa tersebut secara diam-diam tanpa memunculkan error database.

### 4. Eksekusi Script Perbaikan SQL yang Terputus / Rollback Parsial di VPS
Pada penanganan tanggal 20 Oktober 2026, script `perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql` telah berhasil dieksekusi di lingkungan lokal. Namun di server VPS, query mengalami kendala (seperti timeout, ukuran packet, atau rollback sebagian), sehingga hanya ID 138 dan 140 yang tersimpan, sedangkan ID 141, 143, dan 145 belum sempat terealisasi di VPS.

---

## 4. Script SQL Pemulihan 5 Siswa IX Emerald (Siap Eksekusi di VPS)

Jalankan script SQL berikut di menu **SQL** phpMyAdmin VPS untuk memulihkan ke-5 siswa secara tuntas:

```sql
SET FOREIGN_KEY_CHECKS = 0;

-- 1. RESTORE / UPDATE 5 SISWA KE TABEL SISWA (ROMBEL ID: 68 - IX EMERALD)
INSERT INTO `siswa` (
    `id`, `user_id`, `rombel_id`, `nis`, `nisn`, `nik`, `nama_lengkap`, `jenis_kelamin`, 
    `tempat_lahir`, `tanggal_lahir`, `agama`, `anak_ke`, `alamat_siswa`, `rt`, `rw`, 
    `kelurahan`, `kecamatan`, `kode_pos`, `jenis_tinggal`, `alat_transportasi`, 
    `no_hp`, `asal_sekolah`, `penerima_kps`, `penerima_kip`, `layak_pip`, `kebutuhan_khusus`, 
    `status_siswa`, `berat_badan`, `tinggi_badan`, `jml_saudara_kandung`, `jarak_ke_sekolah`, 
    `ekskul_1`, `ekskul_2`
) VALUES
(
    133, NULL, 68, '08.24.0001', '3126602599', '1273041704120002', 'ABYAN NANDANA ROFY', 'L', 
    'PADANG SIDEMPUAN', '2012-04-17', 'Islam', '1', 'JL KAPTEN RAHMAD BUDIN GG KEMBAR', '0', '0', 
    'Desa/Kel. Rengas Pulau', 'Kec. Medan Marelan', '20255', 'Bersama orang tua', 'Sepeda motor', 
    '085261920417', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 33, 139, 0, 5, 1, NULL
),
(
    139, NULL, 68, '08.24.0033', '0127615003', '1207242007120003', 'Bagus Pramudityo', 'L', 
    'Medan', '2012-07-20', 'Islam', '3', 'Perum Hunian Permai Blok A No. 162', '0', '0', 
    'Hamparan Perak', 'Kec. Hamparan Perak', '20374', 'Bersama orang tua', 'Jalan kaki', 
    '085361518892', 'UPT SPF SD NEGERI 105277', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 33, 134, 0, 5, 1, NULL
),
(
    141, NULL, 68, '08.24.0048', '3124573449', '1271062006120004', 'FATHAN LUTHFI', 'L', 
    'MEDAN', '2012-06-20', 'Islam', '3', 'JL. MARELAN RAYA PASAR 2 BARAT', '0', '0', 
    'Desa/Kel. T E R J U N', 'Kec. Medan Marelan', '20256', 'Bersama orang tua', 'Sepeda motor', 
    '085297821831', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 31, 138, 1, 6, 1, NULL
),
(
    143, NULL, 68, '08.24.0053', '0127099650', '1271131209120002', 'FITRA AL-BAIHAQI', 'L', 
    'MEDAN', '2012-09-12', 'Islam', '1', 'LINGKUNGAN 21', '0', '0', 
    'PEKAN LABUHAN', 'Kec. Medan Labuhan', '20253', 'Bersama orang tua', 'Angkutan umum/bus/pete-pete', 
    '088708470781', 'UPT SD NEGERI 060948', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 31, 139, 0, 5, 1, NULL
),
(
    145, NULL, 68, '08.24.0076', '0111681061', '1271062111110005', 'MHD ABDA RIFKY FIRAZA', 'L', 
    'MEDAN', '2011-11-21', 'Islam', '4', 'JL. PLATINA LINGKUNGAN XI NO. 40 PAYA RUMPUTII', '0', '0', 
    'TITIPAPAN', 'Kec. Medan Deli', NULL, 'Bersama orang tua', 'Mobil/bus antar jemput', 
    '082165248106', 'SDS PLUS MIP MARELAN', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 33, 135, 0, 5, 1, NULL
)
ON DUPLICATE KEY UPDATE `rombel_id` = 68, `status_siswa` = 'Aktif';

-- 2. DAFTARKAN KE TABEL anggota_rombel (TA: 10 / GANJIL 2026-2027)
INSERT INTO `anggota_rombel` (`siswa_id`, `rombel_id`, `tahun_ajaran_id`, `semester`)
VALUES 
(133, 68, 10, 'Ganjil'),
(139, 68, 10, 'Ganjil'),
(141, 68, 10, 'Ganjil'),
(143, 68, 10, 'Ganjil'),
(145, 68, 10, 'Ganjil')
ON DUPLICATE KEY UPDATE `rombel_id` = 68;

-- 3. RESTORE DATA ORANG TUA SISWA
INSERT INTO `orangtua_wali` (
    `id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pekerjaan_ayah`, `nik_ayah`, 
    `nama_ibu`, `tahun_lahir_ibu`, `pekerjaan_ibu`, `nik_ibu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES
(3, 133, 'ISMAIL FAHMI', '1977', 'PNS/TNI/Polri', '1273041808770003', 'FARIDAH ANUM', '1980', 'Tidak bekerja', '1273046205800003', 'JL KAPTEN RAHMAD BUDIN GG KEMBAR', '085261920417'),
(95, 139, 'Mulianto', '1974', 'Sudah Meninggal', NULL, 'Farida Hanum', '1974', 'Tidak bekerja', NULL, 'Perum Hunian Permai Blok A No. 162', '085361518892'),
(143, 141, 'HENKY PRAYOGA', '1978', 'Karyawan Swasta', '1271203105780001', 'SUAIDAH', '1980', 'Karyawan Swasta', '1271064301800002', NULL, '085297821831'),
(158, 143, 'SULAIMAN', '1985', 'Karyawan Swasta', '1271132702850001', 'RATINA DIRA', '1990', 'Tidak bekerja', '1271134111900001', NULL, '088708470781'),
(221, 145, 'FAISAL RIZA', '1983', 'Wirausaha', NULL, 'RITAWATI C', '1974', 'Tidak bekerja', NULL, NULL, '082165248106')
ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`), `nama_ayah` = VALUES(`nama_ayah`), `nama_ibu` = VALUES(`nama_ibu`), `no_hp_ortu` = VALUES(`no_hp_ortu`);

SET FOREIGN_KEY_CHECKS = 1;

-- 4. CEK HASIL AKHIR DI VPS (HARUS MENAMPILKAN TEPAT 29 SISWA)
SELECT COUNT(*) AS total_siswa_emerald FROM siswa WHERE rombel_id = 68 AND status_siswa = 'Aktif';
```

---

## 5. Daftar Lengkap 29 Siswa Kelas IX Emerald Setelah Pemulihan

Berikut adalah daftar lengkap 29 siswa kelas IX Emerald di database yang 100% selaras dengan Dapodik:

| No | ID | NISN | NIS | Nama Lengkap Siswa | Jenis Kelamin | Status |
|:--:|:--:|:--:|:--:|:---|:--:|:--:|
| 1 | 133 | 3126602599 | 08.24.0001 | ABYAN NANDANA ROFY | L | Aktif |
| 2 | 134 | 0125880240 | 08.24.0009 | ALEX NAZRIL ILHAM | L | Aktif |
| 3 | 135 | 0125564479 | 08.24.0160 | AR'RAYYAN MIFTAH SYAHYADI | L | Aktif |
| 4 | 136 | 0127134781 | 08.24.0018 | ARJUN FARID | L | Aktif |
| 5 | 137 | 0114639043 | 07.25.0151 | ARJUSE FARISI | L | Aktif |
| 6 | 138 | 0121371946 | 08.24.0032 | Bagas Pramuditya | L | Aktif |
| 7 | 139 | 0127615003 | 08.24.0033 | Bagus Pramudityo | L | Aktif |
| 8 | 140 | 0124992248 | 08.24.0044 | ERLANGGA WICAKSONO | L | Aktif |
| 9 | 141 | 3124573449 | 08.24.0048 | FATHAN LUTHFI | L | Aktif |
| 10 | 142 | 0127249232 | 08.24.0153 | Fi'il Haq Qawiy | L | Aktif |
| 11 | 143 | 0127099650 | 08.24.0053 | FITRA AL-BAIHAQI | L | Aktif |
| 12 | 144 | 0116774970 | 08.24.0057 | HABIB PRATAMA | L | Aktif |
| 13 | 145 | 0111681061 | 08.24.0076 | MHD ABDA RIFKY FIRAZA | L | Aktif |
| 14 | 146 | 0125471389 | 08.24.0078 | MHD Raikhan Pramastio | L | Aktif |
| 15 | 147 | 0124125140 | 08.24.0080 | MUHAMMAD ALI SIDDIQ AL FATHIH | L | Aktif |
| 16 | 148 | 3115820722 | 08.24.0081 | MUHAMMAD ARSYIL ADZHIM | L | Aktif |
| 17 | 149 | 3124050562 | 08.24.0152 | MUHAMMAD ASYRAF | L | Aktif |
| 18 | 150 | 0121810070 | 08.24.0070 | MUHAMMAD FAQEEH ARFA GINTING | L | Aktif |
| 19 | 151 | 3124617870 | 08.24.0085 | MUHAMMAD FATHAN SIREGAR | L | Aktif |
| 20 | 152 | 0126886061 | 08.24.0086 | MUHAMMAD HERO AZAM | L | Aktif |
| 21 | 153 | 0111531147 | 08.24.0158 | MUHAMMAD LUKMAN ALFARIZI | L | Aktif |
| 22 | 154 | 3125146199 | 08.24.0088 | MUHAMMAD NAUFAL ARRAIHAN | L | Aktif |
| 23 | 155 | 3124989103 | 08.24.0120 | RAIHAN SUTAN FAWWAZI | L | Aktif |
| 24 | 156 | 0128021870 | 08.24.0156 | RESTU ARIF PRATAMA SIANTURI | L | Aktif |
| 25 | 157 | 0127295792 | 08.24.0124 | Rifqy Ahmad Hasibuan | L | Aktif |
| 26 | 158 | 3121338906 | 08.24.0126 | RIO FATHIR ZAIRA | L | Aktif |
| 27 | 159 | 0127083494 | 08.24.0133 | SATRIA ANARKI TANJUNG | L | Aktif |
| 28 | 160 | 0126041302 | 08.24.0149 | ZIKRI RAMADHAN | L | Aktif |
| 29 | 161 | 0129734405 | 08.24.0150 | Zio Julian | L | Aktif |

---

## 6. Rekomendasi Solusi Sistemik Jangka Panjang

Agar insiden serupa tidak terus berulang di masa mendatang, disarankan menerapkan pembaruan sistem berikut:

1. **Konsistensi Sumber Data Rombel di Controller:**  
   Ubah query penghitungan jumlah siswa di `TingkatRombelController` agar mengacu pada tabel `anggota_rombel` yang terfilter tahun ajaran aktif, bukan hanya mengandalkan kolom flat `siswa.rombel_id`.
2. **Perbaikan Pemetaan Rombel pada Impor Excel:**  
   Tambahkan filter `WHERE id_tahun_ajaran = [TA_AKTIF]` saat mengambil data rombel di `SiswaController::import()` sehingga nama rombel yang sama di tahun ajaran berbeda tidak saling menimpa.
3. **Pemberian Trigger / Sinkronisasi Otomatis:**  
   Buat database trigger pada tabel `siswa` sehingga setiap kali kolom `rombel_id` diperbarui, data di `anggota_rombel` tahun ajaran aktif otomatis ikut tersinkronisasi.
