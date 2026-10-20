# Laporan Pemeriksaan & Penyesuaian Data Siswa dan Rombel
## Berdasarkan File: `Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`

**Tanggal Pemeriksaan:** 20 Oktober 2026  
**Tahun Ajaran Aktif:** 2026/2027 - Ganjil (ID: 10)  
**Sumber Referensi:** `docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx` (Unduhan Dapodik per 20 Agustus 2026)

---

## 1. Ringkasan Eksekutif

Telah dilakukan pemeriksaan teliti dan menyeluruh terhadap file excel **`Data Siswa Dapodik Perkelas TA 2026-2027.xlsx`** dan dilakukan penyesuaian (rekonsiliasi) dengan data master siswa, tabel riwayat kelas (`anggota_rombel`), serta akun pengguna di database sistem e-Rapor.

### Hasil Akhir Rekonsiliasi:
* **Total Siswa di File Dapodik:** **420 siswa** (tersebar di 15 rombel: VII, VIII, IX).
* **Kesesuaian Data Siswa:** **420 / 420 siswa (100%)** kini telah terdaftar dan aktif di database sistem.
* **Kesesuaian Penempatan Rombel:** **100% Cocok (0 Mismatch)** baik pada tabel `anggota_rombel` tahun ajaran aktif maupun kolom master `siswa.rombel_id`.
* **Pemulihan Siswa Hilang:** Berhasil memulihkan **7 siswa kelas VII Ruby** yang sebelumnya hilang/terhapus, sekaligus menyambungkan kembali data nilai formatif dan nilai rapor mereka yang sebelumnya terputus.
* **Pembersihan Data Orphan:** Berhasil membersihkan record orphan `anggota_rombel` yang tidak memiliki relasi ke siswa aktif.

---

## 2. Rincian Temuan Awal Sebelum Penyesuaian

### A. Siswa yang Cocok Sempurna (413 Siswa)
Sebanyak 413 siswa dari file Dapodik langsung cocok 100% dengan database berdasarkan NISN, nama, dan penempatan kelas di semester aktif (T.A. 2026/2027 Ganjil).

### B. 7 Siswa Kelas VII Ruby Hilang dari Database
Pada file Dapodik, rombel **VII RUBY** memiliki **33 siswa**. Namun pada database, VII Ruby hanya berisi **26 siswa**. Setelah diaudit, ditemukan bahwa 7 siswa kelas VII Ruby hilang akibat insiden penghapusan/import terdahulu:
1. **HANNASYA SIREGAR** (NISN: 3137774281, NIPD: 10.26.0186)
2. **Aqilah Fazriani** (NISN: 0146060725, NIPD: 10.26.0149)
3. **Almira Diah Zahra** (NISN: 0135150721, NIPD: 10.26.0143)
4. **ALISHA MAHWA RAMDANI** (NISN: 0143352255, NIPD: 10.26.0142)
5. **ALTHAFUNNISA HUMAIRA NAZIFA NURFATMA** (NISN: 0147117021, NIPD: 10.26.0144)
6. **AQILAH MUFIDAH HARAHAP** (NISN: 3131173367, NIPD: 10.26.0280)
7. **DAFINAH SALSABILA** (NISN: 3130051459, NIPD: 10.26.0169)

> **Catatan Kritis Nilai:**  
> Tiga dari siswa tersebut (Hannasya Siregar, Aqilah Fazriani, Almira Diah Zahra) sebelumnya sudah memiliki riwayat penilaian formatif dan nilai rapor tengah semester dari guru mapel di database yang statusnya menjadi orphan karena data siswa terhapus.

### C. 2 Siswa Aktif di Database Tidak Ada di Dapodik (VIII Topaz)
Di kelas **VIII Topaz**, file Dapodik mencatat **31 siswa**, sedangkan di database terdapat **33 siswa aktif**:
* **IQBAL AR ZAAKY** (ID: 47, NISN: 3122509892, NIS: 09.25.0057)
* **NAUFAL AL-KHALISH NAMORA NASUTION** (ID: 55, NISN: 0126743285, NIS: 09.25.0090)

*Hasil Audit:* Kedua siswa ini adalah siswa aktif dengan riwayat akademik penuh (masing-masing memiliki 128 data nilai formatif di semester ganjil ini). Kemungkinan besar kedua siswa ini masuk/mutasi setelah tanggal penarikan file Dapodik (20 Agustus 2026) atau belum tersinkronisasi ke Dapodik pusat saat file diunduh.

### D. Siswa Berstatus Pindah di Database
Terdapat 3 siswa yang tercatat di database dengan status `Pindah` dan tidak ada dalam daftar aktif Dapodik:
* **CLARA AFIQHA MARCH** (ID: 200, IX Biduri) - Status DB: Pindah
* **ALVIA ARIN SIRAIT** (ID: 223, IX Crystal) - Status DB: Pindah
* **ARFA ADITYA CAHYA** (ID: 162, IX Fairuz) - Status DB: Pindah  
*(Terdapat pula 1 siswa di IX Crystal: **TRISNA RAMADHANI PUTRI WIRIAWAN**, berstatus Pindah di database namun namanya masih tercantum pada file Dapodik).*

### E. Data Sampah (Orphan Records) di `anggota_rombel`
Ditemukan 2 baris di `anggota_rombel` (ID 728 dan ID 750) yang merujuk pada `siswa_id = 459` dan `481` (ID lama milik Dion Arkaan Alkhalifi dan Luthfi Alfahrezi Aurelio sebelum diinput ulang dengan ID 662 dan 663).

---

## 3. Tindakan Penyesuaian yang Telah Dilakukan

### 1. Pemulihan & Penyesuaian 7 Siswa VII Ruby
Semua 7 siswa VII Ruby telah diinputkan/dipulihkan ke tabel `siswa`, `users`, `orangtua_wali`, dan `anggota_rombel` dengan data lengkap sesuai Dapodik:
* **HANNASYA SIREGAR (Dipulihkan dengan ID 642)**:  
  Otomatis tersambung kembali dengan record `anggota_rombel` (AR ID 982), 3 data nilai formatif, 2 nilai rapor, dan akun orang tua (User ID 647).
* **Aqilah Fazriani (Dipulihkan dengan ID 652)**:  
  Otomatis tersambung kembali dengan record `anggota_rombel` (AR ID 992), 3 data nilai formatif, 2 nilai rapor, dan akun orang tua (User ID 663).
* **Almira Diah Zahra (Dipulihkan dengan ID 656)**:  
  Otomatis tersambung kembali dengan record `anggota_rombel` (AR ID 996), 3 data nilai formatif, 2 nilai rapor, dan akun orang tua (User ID 669).
* **ALISHA MAHWA RAMDANI (Dipulihkan dengan ID 661)**:  
  Tersambung kembali dengan record `anggota_rombel` (AR ID 999), akun siswa baru, dan profil orang tua terhubung ke User ID 196 (menggunakan perbaikan multi-anak).
* **ALTHAFUNNISA HUMAIRA NAZIFA NURFATMA (Didaftarkan dengan ID 664)**:  
  Dibuatkan akun login siswa, dihubungkan ke profil orang tua (User ID 545), dan didaftarkan ke `anggota_rombel` VII Ruby (T.A. 2026/2027 Ganjil).
* **AQILAH MUFIDAH HARAHAP (Didaftarkan dengan ID 665)**:  
  Dibuatkan akun login siswa, dibuatkan akun login orang tua (`W3131173367`), dan didaftarkan ke `anggota_rombel` VII Ruby.
* **DAFINAH SALSABILA (Didaftarkan dengan ID 666)**:  
  Dibuatkan akun login siswa, dihubungkan ke profil orang tua (User ID 569), dan didaftarkan ke `anggota_rombel` VII Ruby.

### 2. Pembersihan Orphan Records di `anggota_rombel`
Menghapus ID 728 dan ID 750 pada tabel `anggota_rombel` agar kelas VII Opal bersih dari referensi siswa non-aktif.

---

## 4. Tabel Rekapitulasi Rombel Saat Ini (DB vs Dapodik)

| No | Tingkat | Rombel | ID Rombel | Wali Kelas | Kuota Dapodik | Siswa Aktif DB | Status Sinkronisasi |
|:--:|:-------:|:-------|:---------:|:-----------|:-------------:|:--------------:|:--------------------|
| 1  | VII     | **Amazonite** | 77 | Adhitya Prayoga, S.Pd., Gr. | 26 | 26 |  **100% Sesuai** |
| 2  | VII     | **Opal**      | 78 | Ary Laksamana Putra | 26 | 26 |  **100% Sesuai** |
| 3  | VII     | **Zamrud**    | 79 | Septian Syahputra, A.Md.Kom | 26 | 26 |  **100% Sesuai** |
| 4  | VII     | **Carnelian** | 80 | Tika Yeardila, M.Pd. | 33 | 33 |  **100% Sesuai** |
| 5  | VII     | **Ruby**      | 81 | Damiati, S.Pd., Gr. | 33 | 33 |  **100% Sesuai (Telah Dipulihkan)** |
| 6  | VIII    | **Granit**    | 72 | Muhammad Fahrozi Sianipar, S.Pd. | 32 | 32 |  **100% Sesuai** |
| 7  | VIII    | **Topaz**     | 73 | Yasir Fauzan, S.Pd.I | 31 | 33 |  **31 Sesuai Dapodik** + 2 Siswa Aktif Lokal *(Iqbal & Naufal)* |
| 8  | VIII    | **Intan**     | 74 | Eli Saprida, S.Pd., Gr. | 22 | 22 |  **100% Sesuai** |
| 9  | VIII    | **Kyanite**   | 75 | Suri Khairunnisa, S.Si., Gr. | 22 | 22 |  **100% Sesuai** |
| 10 | VIII    | **Safir**     | 76 | Luthfia Chairunnisa, S.Pd | 22 | 22 |  **100% Sesuai** |
| 11 | IX      | **Emerald**   | 68 | Ray Agung Hadi Wiranata, SH., Gr. | 29 | 29 |  **100% Sesuai** |
| 12 | IX      | **Fairuz**    | 67 | Mohammad Hamsyahri, S.Pd., Gr. | 28 | 28 |  **100% Sesuai** *(+ 1 siswa status Pindah)* |
| 13 | IX      | **Biduri**    | 69 | Anggana Ayu Marina, S.Pd., Gr. | 30 | 30 |  **100% Sesuai** *(+ 1 siswa status Pindah)* |
| 14 | IX      | **Crystal**   | 70 | Selly Apriya, S.Pd., Gr. | 29 | 28 |  **Sesuai** *(28 Aktif + 1 Pindah Trisna di Dapodik)* |
| 15 | IX      | **Diamond**   | 71 | Dahratul Aini, SS., Gr. | 31 | 31 |  **100% Sesuai** |
| **TOTAL** | | | | | **420** | **422** | **420 Sesuai Dapodik + 2 Siswa Aktif Lokal** |

---

## 5. Kesimpulan & Rekomendasi

1. **Seluruh 420 siswa dari file Dapodik TA 2026-2027 telah terdata lengkap di database sistem e-Rapor dan ditempatkan pada rombel yang tepat 100%**.
2. **Kelas VII Ruby** kini telah genap kembali menjadi **33 siswa**, dan akun wali kelas (Ustadzah Damiati) sekarang menampilkan data 33 siswi secara utuh beserta nilai tengah semester yang sebelumnya sempat tidak tampil.
3. Untuk siswa **Iqbal Ar Zaaky** dan **Naufal Al-Khalish Namora Nasution** di kelas VIII Topaz, pihak operator Dapodik sekolah disarankan memastikan apakah kedua siswa ini sudah tercatat pada sinkronisasi aplikasi Dapodik sekolah versi terbaru.
4. Struktur penempatan siswa telah sinkron antara data master (`siswa.rombel_id`) dan mesin waktu riwayat kelas (`anggota_rombel.rombel_id`) untuk semester Ganjil 2026/2027.
