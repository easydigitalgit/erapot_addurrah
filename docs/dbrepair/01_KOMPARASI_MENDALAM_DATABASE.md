# LAPORAN KOMPARASI MENDALAM: DATABASE LOKAL VS DATABASE VPS (27 OKTOBER 2026, 21:49)

**Aplikasi:** eRapor SMP IT Addurrah  
**Tanggal Snapshot VPS:** 27 Oktober 2026 Pukul 21:49 WIB  
**Folder Analisis:** `docs/db271026-2149/` (`raporsmpit.sql` dan split per-tabel)  
**Tujuan:** Investigasi mendalam penyebab Lingkup Materi (LM) hilang, 24 data siswa hilang di 6 kelas, 142 duplikasi anggota rombel, serta nilai berantakan dan terhapus akibat *foreign key cascade*.

---

## 1. RINGKASAN PERBANDINGAN JUMLAH BARIS SELURUH TABEL

Berikut adalah perbandingan jumlah baris antara **Database Lokal** (`raporsmpit`) dan **Database VPS** (`raporsmpit_vps` dump 27-10-2026 21:49):

| Nama Tabel | Baris Lokal | Baris VPS | Selisih (Lokal - VPS) | Keterangan Status & Dampak |
|---|---|---|---|---|
| `siswa` | **567** | **543** | **+24** | **KRITIS:** 24 siswa aktif hilang di VPS pada 6 kelas rombel. |
| `master_lm` | **318** | **267** | **+51** | **KRITIS:** 54 LM tingkat 8 & 9 hilang di VPS, 3 LM baru diinput di VPS. |
| `nilai_sumatif` | **15.263** | **13.679** | **+1.584** | **KRITIS:** 1.584 nilai sumatif musnah di VPS akibat FK `ON DELETE CASCADE` saat siswa dihapus. |
| `nilai_formatif` | **57.974** | **58.424** | **-450** | Guru telah menginput nilai formatif baru di VPS (B. Arab, Nahwu, PJOK, MTK, SBK). Di VPS terdapat **3.376 nilai orphan**! |
| `nilai_rapor` | **17.102** | **17.740** | **-638** | Nilai rapor terhitung di VPS, namun terdapat **1.139 nilai orphan** di VPS tanpa relasi siswa yang valid. |
| `absensi_harian` | **1.300** | **956** | **+344** | 344 rekaman absensi terhapus di VPS akibat cascade delete siswa. |
| `nilai_ekskul` | **1.184** | **1.123** | **+61** | 61 nilai ekskul terhapus di VPS untuk 24 siswa yang hilang. |
| `anggota_rombel` | **990** | **990** | **0** | **ANOMALI:** 142 pasang duplikat `(rombel_id, siswa_id)` ada di kedua db; siswa hilang tetap tersangkut di tabel ini. |
| `orangtua_wali` | **543** | **543** | **0** | Data orang tua 24 siswa masih tersimpan utuh di VPS (karena tidak ada FK cascade). |
| `rombel` | **39** | **39** | **0** | Struktur rombel sama (15 kelas aktif TA 2026/2027 Ganjil). |
| `tahun_ajaran` | **6** | **6** | **0** | TA Aktif sama: ID 10 (2026/2027 Ganjil). |
| `mata_pelajaran` | **22** | **22** | **0** | Master mata pelajaran identik. |
| `users` | **447** | **446** | **+1** | 1 akun user siswa di lokal belum ada di VPS. |
| `catatan_rapor` | **598** | **622** | **-24** | Catatan rapor di VPS bertambah diinput oleh wali kelas. |
| `login_logs` | **2.175** | **2.252** | **-77** | Log aktivitas login di VPS terus bertambah. |

---

## 2. DETAIL TEMUAN 1: HILANGNYA 24 DATA SISWA DI VPS

### A. Rincian 24 Siswa Hilang Berdasarkan Rombel
Penghapusan siswa pada master `siswa` di VPS terjadi pada 6 kelas rombel aktif TA 2026/2027:

```
Total Siswa Hilang: 24 Siswa
- Kelas VIII Granit (Rombel ID 72): 5 Siswa
- Kelas VIII Topaz (Rombel ID 73):  2 Siswa
- Kelas VIII Intan (Rombel ID 74):  3 Siswa
- Kelas VIII Safir (Rombel ID 76):  9 Siswa
- Kelas IX Carnelian (Rombel ID 80): 3 Siswa
- VII Amazonite (Rombel ID 77):     2 Siswa
```

Daftar lengkap 24 siswa yang hilang dari tabel `siswa` di VPS:

| ID Siswa | Rombel | Nama Rombel | NIS | NISN | Nama Siswa | Nilai Sumatif Terhapus | Nilai Formatif Jadi Orphan | Nilai Rapor Jadi Orphan |
|---|---|---|---|---|---|---|---|---|
| **4** | 72 | VIII Granit | 09.25.0010 | 0121721096 | AKIL ZAYDAN RAHMANA | 50 | 156 | 51 |
| **9** | 72 | VIII Granit | 09.25.0036 | 0131889596 | BARIQ HIJRI AKBAR | 0 | 156 | 51 |
| **11** | 72 | VIII Granit | 09.25.0042 | 3135832794 | ELKAN ABSANA TANJUNG | 0 | 156 | 51 |
| **12** | 72 | VIII Granit | 09.25.0045 | 3122320841 | Fahrian Haikal Ginting | 0 | 156 | 51 |
| **24** | 72 | VIII Granit | 09.25.0074 | 3133618465 | MUHAMMAD RIYAADH SARAGIH | 50 | 156 | 51 |
| **40** | 73 | VIII Topaz | 10.26.0172 | 0122287413 | FADHIL RAJABI | 6 | 142 | 34 |
| **44** | 73 | VIII Topaz | 10.26.0174 | 3133276633 | Fathir Al Ghifary Lesmana | 1 | 144 | 36 |
| **72** | 74 | VIII Intan | 09.25.0056 | 3138693760 | INAYA AZMI ATHIFA | 0 | 162 | 67 |
| **75** | 74 | VIII Intan | 09.25.0069 | 3128350299 | KHEYSA RASHIFA | 0 | 162 | 67 |
| **77** | 74 | VIII Intan | 09.25.0092 | 3131800680 | NAURA DZAKIYYA SAKHI | 0 | 162 | 67 |
| **114** | 76 | VIII Safir | 09.25.0016 | 0133615982 | AMELIA FENETA ZULHAM | 0 | 148 | 51 |
| **118** | 76 | VIII Safir | 09.25.0033 | 0137568545 | Azqa Aqilla Mumtaz | 49 | 148 | 51 |
| **119** | 76 | VIII Safir | 09.25.0041 | 3137946898 | EARLYTA ARSYFA | 6 | 148 | 51 |
| **120** | 76 | VIII Safir | 09.25.0047 | 3121543324 | FARADILLA RAMADANI | 49 | 148 | 51 |
| **121** | 76 | VIII Safir | 09.25.0055 | 3132717393 | Inaya Athifa | 0 | 148 | 51 |
| **122** | 76 | VIII Safir | 09.25.0059 | 0122143093 | Jahira Humairoh Manurung | 49 | 148 | 51 |
| **123** | 76 | VIII Safir | 09.25.0068 | 3125211993 | KHEYLA RASHIFA | 0 | 148 | 51 |
| **124** | 76 | VIII Safir | 09.25.0093 | 3134371569 | NUR ALFIYAH HAWA | 49 | 148 | 51 |
| **125** | 76 | VIII Safir | 09.25.0101 | 3128911048 | RHALYNSA RIZKI | 49 | 148 | 51 |
| **440** | 80 | IX Carnelian | 10.26.0155 | 0113110940 | Ashillah Ratu Balqis | 8 | 52 | 16 |
| **444** | 80 | IX Carnelian | 09.26.00009 | 0114002636 | HAURA SYAKIRAH HUMAIRA | 13 | 52 | 16 |
| **446** | 80 | IX Carnelian | 09.26.00011 | 0114294025 | JASMINE AZZAHRA SIREGAR | 13 | 52 | 16 |
| **534** | 77 | VII Amazonite | 13.26.00026 | 0145695039 | ARKA ZEVAN ABADI | 12 | 52 | 15 |
| **540** | 77 | VII Amazonite | 10.26.0176 | 0137330756 | EMERY ALDRIC MUSYAFFA | 11 | 52 | 15 |

### B. Dampak Foreign Key Cascade
Relasi database di `_relasi.txt`:
```sql
`nilai_sumatif`.`siswa_id` -> `siswa`.`id` [ON DELETE CASCADE]
`absensi_harian`.`siswa_id` -> `siswa`.`id` [ON DELETE CASCADE]
```
Ketika 24 siswa terhapus dari tabel `siswa` di VPS:
1. **Nilai Sumatif:** Seluruh baris nilai sumatif milik 24 siswa tersebut (415 baris) langsung terhapus secara permanen otomatis oleh database MySQL.
2. **Nilai Formatif:** Karena tabel `nilai_formatif` TIDAK memiliki foreign key cascade, **3.376 baris nilai formatif** menjadi record yatim (*orphan*), tidak bisa ditampilkan di aplikasi eRapor.
3. **Nilai Rapor:** Sebanyak **1.139 baris nilai rapor** menjadi record yatim (*orphan*).
4. **Anggota Rombel:** Ke-24 siswa masih tercatat ID-nya di tabel `anggota_rombel` di VPS, namun ketika sistem melakukan query `JOIN siswa`, siswa tersebut tidak muncul di daftar kelas.

---

## 3. DETAIL TEMUAN 2: MASTER LINGKUP MATERI (LM) HILANG

### A. Perbandingan Distribusi Master LM Lokal vs VPS

| Tingkat | Semester | Kategori | Jumlah di Lokal | Jumlah di VPS | Status Selisih |
|---|---|---|---|---|---|
| **7** | Ganjil | Tengah | 30 LM | 30 LM | Sinkron (Lengkap) |
| **7** | Ganjil | Akhir | 37 LM | 37 LM | Sinkron (Lengkap) |
| **7** | Genap | Tengah | 28 LM | 28 LM | Sinkron (Lengkap) |
| **7** | Genap | Akhir | 32 LM | 32 LM | Sinkron (Lengkap) |
| **8** | Ganjil | Tengah | **24 LM** | **4 LM** | **HILANG 20 LM di VPS** |
| **8** | Ganjil | Akhir | 34 LM | 33 LM | Hilang 1 LM di VPS |
| **8** | Genap | Tengah | **25 LM** | **6 LM** | **HILANG 19 LM di VPS** |
| **8** | Genap | Akhir | 29 LM | 29 LM | Sinkron (Lengkap) |
| **9** | Ganjil | Tengah | **17 LM** | **6 LM** | **HILANG 11 LM di VPS** |
| **9** | Ganjil | Akhir | 34 LM | 34 LM | Sinkron (Lengkap) |
| **9** | Genap | Akhir | 28 LM | 28 LM | Sinkron (Lengkap) |
| **TOTAL** | | | **318 LM** | **267 LM** | **Bersih: 54 Hilang, 3 Ditambah di VPS** |

### B. Penyebab Hilangnya LM di VPS
1. **Bug Regex Angka Romawi:** Pada kode sebelum perbaikan, import Excel LM membaca tingkat `VIII` dan `IX` sebagai bukan angka, sehingga fallback ke `tingkat = 7`. Hal ini menyebabkan LM tingkat 8 dan 9 menimpa LM tingkat 7, sementara record tingkat 8 & 9 di database menjadi kosong.
2. **Kondisi Saat Ini di VPS:** Guru kelas 8 (Granit, Topaz, Intan, Kyanite, Safir) dan kelas 9 saat membuka form input nilai atau cetak rapor hanya mendapati sedikit sekali LM atau bahkan kosong, sehingga nilai sumatif dan deskripsi rapor tidak bisa diproses.
3. **Ada 3 LM Baru di VPS:** Pada tanggal 27 Oktober jam 05:00 WIB, seorang guru telah menginput 3 LM Bahasa Inggris Tingkat 9 Ganjil Tengah secara manual di VPS (ID 362, 363, 364). LM baru ini **harus dipertahankan dan diintegrasikan ke DB Lokal**.

---

## 4. DETAIL TEMUAN 3: DUPLIKASI ANGGOTA ROMBEL

### A. Anomali Tabel `anggota_rombel`
Di kedua database (Lokal dan VPS), ditemukan **142 kasus duplikasi anggota rombel**:
- Setiap siswa terdaftar **2 kali** pada rombel yang sama di tabel `anggota_rombel`.
- Kelas yang mengalami duplikasi massal:
  - **Kelas Ruby (Rombel ID 81):** Seluruh 33 siswa terdaftar ganda (66 baris).
  - **Kelas Carnelian (Rombel ID 80):** Seluruh 33 siswa terdaftar ganda (66 baris).
  - **Kelas Opal (Rombel ID 78), Topaz (Rombel ID 73), Zamrud (Rombel ID 79):** Siswa-siswa tertentu terdaftar ganda.
  - **Kelas Kyanite (Rombel ID 75):** Siswa Assyfa Rahima terdaftar ganda.

### B. Dampak Duplikasi Anggota Rombel
1. Pada saat download Template Nilai Kolektif Excel (seperti Topaz atau Carnelian), query SQL menghasilkan nama siswa ganda berturut-turut.
2. Saat form setoran nilai di-submit, sistem melakukan iterasi dobel yang memicu tabrakan ID atau gagal validasi.

---

## 5. DETAIL TEMUAN 4: KONDISI NILAI SUMATIF, FORMATIF, DAN RAPOR

### A. Nilai Formatif yang Diinput Guru di VPS (27 Oktober 2026)
Meskipun VPS mengalami kehilangan siswa dan LM, para guru aktif menginput nilai formatif pada tanggal **27 Oktober 2026** hingga pukul 16:57 WIB.
Data baru di VPS yang belum ada di lokal:
- **Bahasa Arab (ID 10):** 208 baris nilai formatif baru di TA 10.
- **Nahwu (ID 44):** 216 baris nilai formatif baru di TA 10.
- **PJOK (ID 9):** 2 baris formatif baru.
- **Matematika (ID 4):** 6 baris formatif baru.
- **Seni Budaya (ID 8):** 18 baris formatif baru.
- **Bahasa Arab Sumatif:** 44 baris nilai sumatif baru di VPS.
- **Nahwu Sumatif:** 47 baris nilai sumatif baru di VPS.
- **Matematika Sumatif:** 35 baris nilai sumatif baru di VPS.

> [!IMPORTANT]
> Seluruh nilai baru yang diinput guru di VPS pada tanggal 27 Oktober **TIDAK BOLEH DITIMPA ATAU HILANG**. Kita harus menyatukan (*merge*) data ini dengan data DB lokal.

---

## 6. KETERKAITAN DENGAN FOLDER `data_nilai_sts_ganjil_2026`

Folder `docs/data_nilai_sts_ganjil_2026/` menyediakan berkas master resmi yang diberikan guru-guru untuk semester ganjil ini:
1. **`LM STS Kelas 8 T.A. 2026-2027.xlsx`:** Berisi 19 baris pemetaan LM kelas 8 lengkap seluruh mata pelajaran (PAI, PKn, B. Indo, MTK, IPA, B. Inggris, IPS, PJOK, Informatika, Hadist, B. Arab, Nahwu, Seni Budaya).
2. **`DKN BAHASA ARAB VIII STS Ganjil.xlsx` & `DKN NAHWU VIII STS Ganjil.xlsx`:** Nilai STS kelas 8 B. Arab & Nahwu.
3. **`DKN STS GANJIL IPA KELAS 8 2026-2027.xlsx`:** Nilai IPA kelas 8 lengkap.
4. **`DKN STS Kyanite.xlsx` & `DKN STS Safir.xlsx`:** Nilai STS untuk kelas Kyanite dan Safir (kelas yang sempat bermasalah siswanya).
5. **`Template_Kolektif_Tengah_Kyanite.xlsx`, `Safir.xlsx`, `Topaz fix.xlsx`:** Template kolektif perbaikan yang siap di-import.

Setelah tahapan perbaikan integritas database selesai, data nilai dari folder ini dapat dimasukkan secara aman tanpa kendala siswa hilang atau duplikasi anggota rombel.

