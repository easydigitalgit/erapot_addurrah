# LAPORAN ANALISIS KOMPARASI JUMLAH SISWA PER ROMBEL
## DATABASE eRapor VS EXCEL DAPODIK (UPDATE 27 OKTOBER 2026)

- **Tanggal Analisis:** 28 Oktober 2026  
- **Database Target:** `raporsmpit` (Tahun Ajaran Aktif: 2026/2027 Ganjil, ID: 10)  
- **File Referensi Excel:** `docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx`  
- **Lembar Kerja:** `Data Siswa` (Total: 551 baris data siswa)

---

## 1. RINGKASAN EKSEKUTIF (EXECUTIVE SUMMARY)

Berdasarkan hasil audit silang dan komparasi mendalam antara tabel relasi siswa di database (`siswa`, `anggota_rombel`, `rombel`, `tahun_ajaran`) dengan file Dapodik resmi tanggal 27 Oktober 2026, diperoleh kesimpulan utama sebagai berikut:

1. **Tingkat VII (100% Cocok Sempurna):**
   - Seluruh 5 rombel (Amazonite, Carnelian, Opal, Ruby, Zamrud) memiliki jumlah siswa yang **sama persis (144 siswa)** antara database dan Dapodik.
   - Pencocokan nama per nama dan NIS per NIS menghasilkan **0 selisih**.

2. **Tingkat IX Aktif (100% Cocok Sempurna):**
   - Seluruh 5 rombel (Biduri, Crystal, Diamond, Emerald, Fairuz) memiliki jumlah siswa yang **sama persis (150 siswa)** antara database dan Dapodik.
   - Status 4 siswa pindah (1 di Biduri, 2 di Crystal, 1 di Fairuz) tercatat **identik 100%** baik di Excel Dapodik maupun kolom `status_siswa` database.

3. **Tingkat VIII (Selisih 16 Siswa Riil Non-Dapodik):**
   - Rombel **Kyanite (22 siswa)** dan **Topaz (33 siswa)** cocok sempurna (0 selisih).
   - Rombel **Granit (+4 di DB)**, **Intan (+3 di DB)**, dan **Safir (+9 di DB)** memiliki total kelebihan **16 siswa** di database.
   - **Investigasi:** Ke-16 siswa ini adalah siswa aktif di sekolah yang memiliki nilai rapor/STS lengkap (12–13 mapel) di database, namun **tidak terdaftar di lembar Dapodik 27 Oktober 2026**.

4. **Data Ekstra di Berkas Excel Dapodik (142 Siswa):**
   - **141 Siswa Alumni / Lulusan Kelas IX TA 2025/2026:** Terdaftar di Excel pada rombel IX Amazonite (28), IX Carnelian (30), IX Opal (30), IX Ruby (27), dan IX Zamrud (26). Di database, siswa-siswa ini tersimpan rapi pada arsip TA 2025/2026 Genap (TA ID 9).
   - **1 Siswa Anomali `VII Kyanite`:** Siswa atas nama **ASSYFA RAHIMA** (NIS `09.25.0025`, angkatan 2025) tercatat di Dapodik pada rombel `VII Kyanite`. Di database, ia tersimpan di TA 9 dan belum dimasukkan ke rombel kelas VIII TA 10 (belum ada nilai TA 10).

---

## 2. TABEL KOMPARASI JUMLAH SISWA PER ROMBEL AKTIF (TA 2026/2027)

Tabel berikut membandingkan 15 rombel aktif pada Tahun Ajaran 2026/2027 Ganjil:

| No | Tingkat | Nama Rombel | Jml Siswa DB | DB Aktif | DB Pindah | Jml Siswa Excel | Excel Aktif | Excel Pindah | Selisih (DB - Ex) | Status Kecocokan |
|:--:|:-------:|:------------|:------------:|:--------:|:---------:|:---------------:|:-----------:|:------------:|:-----------------:|:-----------------|
| 1  | VII     | Amazonite   | 26           | 26       | 0         | 26              | 26          | 0            | **0**             | Sesuai Sempurna |
| 2  | VII     | Carnelian   | 33           | 33       | 0         | 33              | 33          | 0            | **0**             | Sesuai Sempurna |
| 3  | VII     | Opal        | 26           | 26       | 0         | 26              | 26          | 0            | **0**             | Sesuai Sempurna |
| 4  | VII     | Ruby        | 33           | 33       | 0         | 33              | 33          | 0            | **0**             | Sesuai Sempurna |
| 5  | VII     | Zamrud      | 26           | 26       | 0         | 26              | 26          | 0            | **0**             | Sesuai Sempurna |
| **Subtotal VII** | | | **144** | **144** | **0** | **144** | **144** | **0** | **0** | **100% Selaras** |
| 6  | VIII    | Granit      | 32           | 32       | 0         | 28              | 28          | 0            | **+4**            | 4 Siswa Non-Dapodik |
| 7  | VIII    | Intan       | 22           | 22       | 0         | 19              | 19          | 0            | **+3**            | 3 Siswa Non-Dapodik |
| 8  | VIII    | Kyanite     | 22           | 22       | 0         | 22              | 22          | 0            | **0**             | Sesuai Sempurna |
| 9  | VIII    | Safir       | 22           | 22       | 0         | 13              | 13          | 0            | **+9**            | 9 Siswa Non-Dapodik |
| 10 | VIII    | Topaz       | 33           | 33       | 0         | 33              | 33          | 0            | **0**             | Sesuai Sempurna |
| **Subtotal VIII** | | | **131** | **131** | **0** | **115** | **115** | **0** | **+16** | **16 Non-Dapodik di DB** |
| 11 | IX      | Biduri      | 31           | 30       | 1         | 31              | 30          | 1            | **0**             | Sesuai Sempurna |
| 12 | IX      | Crystal     | 30           | 28       | 2         | 30              | 28          | 2            | **0**             | Sesuai Sempurna |
| 13 | IX      | Diamond     | 31           | 31       | 0         | 31              | 31          | 0            | **0**             | Sesuai Sempurna |
| 14 | IX      | Emerald     | 29           | 29       | 0         | 29              | 29          | 0            | **0**             | Sesuai Sempurna |
| 15 | IX      | Fairuz      | 29           | 28       | 1         | 29              | 28          | 1            | **0**             | Sesuai Sempurna |
| **Subtotal IX** | | | **150** | **146** | **4** | **150** | **146** | **4** | **0** | **100% Selaras** |
| **TOTAL AKTIF** | | | **425** | **421** | **4** | **409** | **405** | **4** | **+16** | |

---

## 3. REKONSILIASI KELAS TAMBAHAN DI EXCEL DAPODIK

Di dalam file Excel Dapodik terdapat total **551 baris siswa**, sedangkan total siswa aktif di database adalah **425 siswa**. Berikut adalah rincian selisihnya:

| Kategori Data Excel | Nama Kelas di Excel | Jml Siswa Excel | Status di DB | Penjelasan Status |
|:-------------------|:--------------------|:---------------:|:------------:|:-------------------|
| **15 Rombel Aktif TA 2026/2027** | VII, VIII, IX (15 Rombel) | 409 | 425 di DB | 409 siswa cocok 100%, +16 siswa riil aktif non-dapodik di DB. |
| **Alumni / Lulusan TA 2025/2026** | IX Amazonite | 28 | TA 9 (Arsip) | Angkatan 2023 (`NIS 07.23`), lulus akhir 2025/2026. |
| | IX Carnelian | 30 | TA 9 (Arsip) | Angkatan 2023 (`NIS 07.23`), lulus akhir 2025/2026. |
| | IX Opal | 30 | TA 9 (Arsip) | Angkatan 2023 (`NIS 07.23`), lulus akhir 2025/2026. |
| | IX Ruby | 27 | TA 9 (Arsip) | Angkatan 2023 (`NIS 07.23`), lulus akhir 2025/2026. |
| | IX Zamrud | 26 | TA 9 (Arsip) | Angkatan 2023 (`NIS 07.23`), lulus akhir 2025/2026. |
| **Subtotal Alumni** | *(5 Rombel Lama)* | **141** | **141 di TA 9** | Masih terbawa di ekspor Dapodik dengan status aktif. |
| **Anomali Siswa Tertinggal** | VII Kyanite | 1 | TA 9 (ID 93) | ASSYFA RAHIMA (`09.25.0025`), belum dipromosikan ke TA 10. |
| **TOTAL DATA EXCEL** | *(21 Rombel)* | **551** | **551 Tervalidasi** | **100% Data Excel Telah Teridentifikasi** |

---

## 4. RINCIAN MENDALAM: 16 SISWA AKTIF KELAS VIII NON-DAPODIK DI DATABASE

Ke-16 siswa di bawah ini tercatat aktif di database pada rombel kelas VIII, memiliki nilai rapor lengkap (12–13 mapel), namun **tidak ada dalam file Dapodik 27 Oktober 2026**:

### A. Kelas VIII Granit (4 Siswa)
| No | ID DB | NIS | Nama Siswa | JK | Status DB | Nilai Rapor TA10 | Keterangan |
|:--:|:-----:|:----|:-----------|:--:|:---------:|:----------------:|:-----------|
| 1  | 4     | `09.25.0010` | AKIL ZAYDAN RAHMANA | L | Aktif | 12 Mapel | Siswa riil aktif di eRapor |
| 2  | 9     | `09.25.0036` | BARIQ HIJRI AKBAR | L | Aktif | 12 Mapel | Siswa riil aktif di eRapor |
| 3  | 11    | `09.25.0042` | ELKAN ABSANA TANJUNG | L | Aktif | 12 Mapel | Siswa riil aktif di eRapor |
| 4  | 12    | `09.25.0045-A` | Fahrian Haikal Ginting | L | Aktif | 12 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |

### B. Kelas VIII Intan (3 Siswa)
| No | ID DB | NIS | Nama Siswa | JK | Status DB | Nilai Rapor TA10 | Keterangan |
|:--:|:-----:|:----|:-----------|:--:|:---------:|:----------------:|:-----------|
| 1  | 72    | `09.25.0056` | INAYA AZMI ATHIFA | P | Aktif | 13 Mapel | Siswa riil aktif di eRapor |
| 2  | 75    | `09.25.0069` | KHEYSA RASHIFA | P | Aktif | 13 Mapel | Siswa riil aktif di eRapor |
| 3  | 77    | `09.25.0092-A` | NAURA DZAKIYYA SAKHI | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |

### C. Kelas VIII Safir (9 Siswa)
| No | ID DB | NIS | Nama Siswa | JK | Status DB | Nilai Rapor TA10 | Keterangan |
|:--:|:-----:|:----|:-----------|:--:|:---------:|:----------------:|:-----------|
| 1  | 114   | `09.25.0016` | AMELIA FENETA ZULHAM | P | Aktif | 13 Mapel | Siswa riil aktif di eRapor |
| 2  | 118   | `09.25.0033` | Azqa Aqilla Mumtaz | P | Aktif | 13 Mapel | Siswa riil aktif di eRapor |
| 3  | 119   | `09.25.0041-A` | EARLYTA ARSYFA | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |
| 4  | 120   | `09.25.0047-A` | FARADILLA RAMADANI | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |
| 5  | 121   | `09.25.0055-A` | Inaya Athifa | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |
| 6  | 122   | `09.25.0059-A` | Jahira Humairoh Manurung | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |
| 7  | 123   | `09.25.0068-A` | KHEYLA RASHIFA | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |
| 8  | 124   | `09.25.0093` | NUR ALFIYAH HAWA | P | Aktif | 13 Mapel | Siswa riil aktif di eRapor |
| 9  | 125   | `09.25.0101-A` | RHALYNSA RIZKI | P | Aktif | 13 Mapel | Diberi suffix `-A` agar tidak bentrok urutan Dapodik |

> **Catatan Teknis Suffix `-A`:**  
> Pada proses pembersihan dan sinkronisasi NIS Dapodik sebelumnya, 8 siswa non-dapodik diberikan akhiran `-A` pada nomor NIS-nya untuk mencegah duplikasi (*unique key collision*) terhadap siswa resmi Dapodik yang menempati nomor urut tersebut, sekaligus melindungi integritas nilai rapor mereka.

---

## 5. DETAIL 4 SISWA DENGAN STATUS PINDAH DI KELAS IX

Pencocokan antara Excel Dapodik dan Database menunjukkan konsistensi status 100%:

| No | Kelas | ID DB | NIS | Nama Siswa | Status Excel | Status DB | Keterangan |
|:--:|:-----:|:-----:|:----|:-----------|:------------:|:---------:|:-----------|
| 1  | IX Biduri | 200 | `08.24.0036` | CLARA AFIQHA MARCH | Pindah | Pindah | Terdata pindah di kedua sumber |
| 2  | IX Crystal | 223 | `08.24.0011` | ALVIA ARIN SIRAIT | Pindah | Pindah | Terdata pindah di kedua sumber |
| 3  | IX Crystal | 247 | `08.24.0138` | TRISNA RAMADHANI PUTRI WIRIAWAN | Pindah | Pindah | Terdata pindah di kedua sumber |
| 4  | IX Fairuz | 162 | `08.24.0157` | ARFA ADITYA CAHYA | Pindah | Pindah | Terdata pindah di kedua sumber |

---

## 6. DETAIL KASUS KHUSUS: ASSYFA RAHIMA (`VII Kyanite` di Excel)

- **Nama Siswa:** ASSYFA RAHIMA  
- **NIS:** `09.25.0025` | **NISN:** `3138721100` | **ID DB:** `93`  
- **Temuan di Excel Dapodik:** Tercatat pada kelas **`VII Kyanite`** dengan status **Aktif**.  
- **Temuan di Database:**  
  - Terdaftar pada rombel `VII Kyanite` di TA 2025/2026 Genap (TA ID: 9).  
  - Memiliki riwayat 26 nilai rapor lengkap pada semester genap tahun lalu.  
  - **Belum masuk ke rombel mana pun pada TA 2026/2027 Ganjil (TA ID: 10)** dan memiliki 0 nilai di TA 10.  
- **Kesimpulan:** Siswa ini merupakan angkatan 2025 yang seharusnya naik ke Kelas VIII, namun di Dapodik masih tertinggal di rombel `VII Kyanite` dan di database belum diikutsertakan ke dalam pembagian rombel TA 2026/2027.

---

## 7. KESIMPULAN DAN REKOMENDASI TINDAK LANJUT

1. **Integritas Rombel Aktif eRapor Sangat Baik:**  
   - Kelas VII (144 siswa) dan Kelas IX (150 siswa) sudah **100% selaras sempurna** antara database dan Dapodik resmi per 27 Oktober 2026.
   - Kelas VIII Kyanite (22) dan Topaz (33) selaras 100%.

2. **Tindak Lanjut 16 Siswa Non-Dapodik Kelas VIII:**  
   - Operator Dapodik sekolah disarankan untuk mengonfirmasi ke bagian Tata Usaha / Kurikulum terkait status pendaftaran Dapodik ke-16 siswa ini.
   - Di aplikasi eRapor, data dan nilai rapor ke-16 siswa ini **wajib tetap dipertahankan** karena proses pembelajaran dan penilaian STS mereka telah berjalan penuh.

3. **Tindak Lanjut Siswa Assyfa Rahima:**  
   - Konfirmasi ke wali kelas / kurikulum apakah siswi yang bersangkutan masih aktif bersekolah di SMP IT Addurrah. Jika masih aktif, tempatkan ke dalam rombel kelas VIII yang sesuai pada TA 2026/2027.

4. **Pengarsipan Rombel Lulusan Dapodik:**  
   - 141 siswa alumni (IX Amazonite, IX Carnelian, IX Opal, IX Ruby, IX Zamrud angkatan 2023) yang masih muncul di berkas Dapodik dapat diabaikan untuk proses eRapor tahun berjalan, karena sistem eRapor telah memisahkan mereka ke arsip TA 2025/2026 Genap (TA ID 9).

