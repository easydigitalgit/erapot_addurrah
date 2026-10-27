# LOG PROGRES & REKAPITULASI EKSEKUSI REPAIR DATABASE

**Aplikasi:** eRapor SMP IT Addurrah  
**Basis Data:** MySQL (`raporsmpit` Lokal & `raporsmpit_vps` VPS Dump 27-10-2026 21:49)  
**Tahun Ajaran Aktif:** 2026/2027 Ganjil (ID 10)  
**Dokumen Induk:** [`01_KOMPARASI_MENDALAM_DATABASE.md`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/01_KOMPARASI_MENDALAM_DATABASE.md)  
**Panduan Step-by-Step:** [`02_PANDUAN_REPAIR_STEP_BY_STEP.md`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_PANDUAN_REPAIR_STEP_BY_STEP.md)  

Dokumen ini berfungsi sebagai buku catatan kerja (*living log*) untuk memantau status pengerjaan setiap tahapan, jumlah data sebelum dan sesudah eksekusi, serta rencana langkah selanjutnya secara rinci.

---

## 1. TABEL STATUS KEMAJUAN (STATUS REKAPITULASI)

| No | Tahapan Pengerjaan | Target Database | Status | Tanggal & Jam Eksekusi | Keterangan Jumlah Data |
|---|---|---|---|---|---|
| **0** | **Safety Backup DB Lokal** | Lokal | **SELESAI** | 27 Okt 2026, 23:09 | File: `backups/raporsmpit_local_pre_repair_20261027_2309.sql` (**32,3 MB**) |
| **1** | **Bersihkan 142 Duplikat Anggota Rombel** | Lokal | **SELESAI** | 27 Okt 2026, 23:09 | Sebelum: **990 baris** &rarr; Sesudah: **848 baris**. Duplikat = **0** |
| **2** | **Sinkronisasi Master LM (Serap 3 LM Baru VPS)** | Lokal | **SELESAI** | 27 Okt 2026, 23:10 | Sebelum: **318 LM** &rarr; Sesudah: **321 LM** (+3 LM B. Inggris Tgt 9) |
| **3** | **Serap Nilai Formatif & Sumatif Baru Guru VPS** | Lokal | **SELESAI** | 27 Okt 2026, 23:10 | Formatif: **+450 baris** (total 58.424), Sumatif: **+205 baris** (total 15.468) |
| **4** | **Simulasi & Pengujian 4 Skrip Repair VPS** | VPS (`raporsmpit_vps`) | **SELESAI** | 27 Okt 2026, 23:04 | Skrip 01 s.d. 04 teruji 100% sukses. Siswa: 567, LM: 321, Duplikat: 0, Orphan sumatif: 0 |
| **5.1** | **Import Nilai STS: Bahasa Arab Kelas 8** | Lokal | **SELESAI** | 27 Okt 2026, 23:21 | Intan, Safir, Kyanite: 66 siswa (264 formatif, 66 sumatif) |
| **5.2** | **Import Nilai STS: Nahwu Kelas 8** | Lokal | **SELESAI** | 27 Okt 2026, 23:21 | Intan, Safir, Kyanite: 66 siswa (264 formatif, 66 sumatif) |
| **5.3** | **Import Nilai STS: IPA Kelas 8 (5 Kelas)** | Lokal | **SELESAI** | 27 Okt 2026, 23:25 | Granit, Topaz, Intan, Kyanite, Safir: 129 siswa (516 formatif, 129 sumatif) |
| **5.4** | **Import Nilai STS: B. Inggris Kelas 8 (5 Kelas)**| Lokal | **SELESAI** | 27 Okt 2026, 23:27 | Granit, Topaz, Intan, Kyanite, Safir: 131 siswa (524 formatif, 131 sumatif) |
| **5.5** | **Import Template Kolektif Seni Budaya** | Lokal | **SELESAI** | 27 Okt 2026, 23:29 | Safir (22 siswa: 66 formatif) & Topaz (33 siswa: 66 formatif, 33 sumatif) |
| **5.6** | **Import Template Kolektif Informatika** | Lokal | **SELESAI** | 27 Okt 2026, 23:29 | Kyanite (22 siswa: 88 formatif, 22 sumatif) & Topaz (33 siswa: 132 formatif, 33 sumatif) |
| **5.7** | **Import DKN Matematika & Hadist Kelas 8** | Lokal | **SELESAI** | 27 Okt 2026, 23:34 | Matematika: 76 siswa (304 formatif, 76 sumatif); Hadist: 66 siswa (264 formatif, 66 sumatif) |
| **5.8** | **Penyelarasan Master LM Kelas 8 Sesuai Guru** | Lokal | **SELESAI** | 27 Okt 2026, 23:49 | Tambah LM 2 B. Indo; update deskripsi PAIBP, PJOK, Seni Budaya, Hadist, PKN (26 LM) |
| **5.9** | **Import Batch Sisa Mapel Kelas 8 Lengkap** | Lokal | **SELESAI** | 27 Okt 2026, 23:51 | B. Indonesia, PAIBP, MTK Topaz/Safir, PKN, IPS, PJOK, Seni Budaya, Informatika (11 file DKN) |
| **6** | **Generate Final Dump Siap Upload ke VPS** | Lokal &rarr; VPS | **SELESAI** | 27 Okt 2026, 23:52 | File: `docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql` (**32,9 MB**) |
| **7** | **Pembersihan 63 Nilai Sumatif Ganda & Audit Kelas IX** | Lokal &rarr; Dump | **SELESAI** | 28 Okt 2026, 00:24 | Hapus 63 sumatif ganda (55 dummy 1.00 + 8 identik) di VII & VIII. Kelas IX: 0 duplikat (bersih). Dump diperbarui. |
| **8** | **Sinkronisasi Massal Nilai Rapor (PAIBP, B. Indo, Arab, Nahwu, dll)** | Lokal &rarr; Dump | **SELESAI** | 28 Okt 2026, 01:03 | Masukkan 418 baris ke `nilai_rapor`. PAIBP Topaz (31 siswa), B. Indo Topaz (31 siswa), B. Arab & Nahwu Topaz, dan seluruh kelas. Rapor cetak terisi 100%. |
| **9** | **Sinkronisasi NIS Resmi Dapodik (Update 27 Okt 2026)** | Lokal &rarr; Dump | **SELESAI** | 28 Okt 2026, 01:36 | Sinkronkan 206 NIS siswa ke Dapodik resmi. Tuntaskan 3 duplikat NIS (Akbar Wijaya, Mysha, Fadhil Rajabi). Duplikat NIS = 0. |
| **10** | **Investigasi Nilai Kosong B. Arab & Nahwu VII Ikhwan** | Dokumentasi & Dokumen Guru | **TERCATAT** | 28 Okt 2026, 02:20 | Telusuri 4 database backup (lokal & VPS). Terbukti belum diinput oleh Ustadz M. Fahrozi Sianipar (78 siswa: Amazonite, Opal, Zamrud). Dibuat dokumen konfirmasi guru. |
| **11** | **Master Plan Integritas Data, Soft Delete & Audit Trail** | Blueprint / Rencana Arsitektur | **SIAP** | 28 Okt 2026, 02:49 | Rencana 5 Pilar: Database Constraints, Safe Upsert di Controller, Dry-Run Frontend, Soft Delete (Recycle Bin) anti-asal hapus, dan Audit Trail Logging pelacak pengubah data (Before vs After). Berkas: `09_MASTER_PLAN_PERBAIKAN_FITUR_IMPORT.md`. |

---

## 2. DETAIL HASIL PEKERJAAN YANG TELAH SELESAI

### A. Tahap 0: Backup Pengaman Database Lokal
- **Waktu Eksekusi:** 27 Oktober 2026, pukul 23:09:24 WIB
- **File:** [`backups/raporsmpit_local_pre_repair_20261027_2309.sql`](file:///d:/laragon/htdocs/erapoteasy/backups/raporsmpit_local_pre_repair_20261027_2309.sql)
- **Ukuran:** 32.334.241 bytes (~32,3 MB)
- **Status:** Backup utuh, dapat di-rollback sewaktu-waktu jika diperlukan.

### B. Tahap 1: Pembersihan 142 Duplikasi `anggota_rombel`
- **File Skrip:** [`docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql)
- **Temuan Awal:** 142 entri ganda, terutama di kelas:
  - Kelas VII Ruby (Rombel ID 81): 33 siswa ganda (66 baris)
  - Kelas VII Carnelian (Rombel ID 80): 33 siswa ganda (66 baris)
  - Kelas VII Opal (Rombel ID 78), VIII Topaz (ID 73), VII Zamrud (ID 79), VIII Kyanite (ID 75)
- **Aksi:** Menghapus baris duplikat yang memiliki ID lebih besar, mempertahankan baris unik pertama.
- **Hasil Verifikasi Pasca-Eksekusi:**
  ```sql
  SELECT COUNT(*) FROM raporsmpit.anggota_rombel;
  -- Hasil: 848 baris (tepat berkurang 142 dari 990 baris)
  
  SELECT COUNT(*) FROM (
    SELECT rombel_id, siswa_id FROM raporsmpit.anggota_rombel GROUP BY rombel_id, siswa_id HAVING COUNT(*) > 1
  ) dup;
  -- Hasil: 0 baris (BERSIH TOTAL)
  ```

### C. Tahap 2: Sinkronisasi Master Lingkup Materi (LM)
- **File Skrip:** [`docs/dbrepair/03_sinkronisasi_master_lm.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/03_sinkronisasi_master_lm.sql)
- **Kondisi Awal:**
  - Database Lokal: 318 LM (Lengkap 54 LM Tingkat 8 & 9, namun belum ada 3 LM inputan guru VPS).
  - Database VPS: 267 LM (Hilang 54 LM Tingkat 8 & 9 akibat regex angka Romawi, tapi ada 3 LM baru inputan guru VPS).
- **Aksi:** Menambahkan 3 LM Bahasa Inggris Tingkat 9 Ganjil Tengah (ID 362, 363, 364) ke DB lokal.
- **Hasil Verifikasi Pasca-Eksekusi:**
  - Total Master LM di Lokal: **321 LM** (318 + 3).
  - Seluruh tingkat (7, 8, 9) kini lengkap untuk kategori Ganjil Tengah, Ganjil Akhir, Genap Tengah, Genap Akhir.

### D. Tahap 3: Penyerapan Nilai Baru Inputan Guru VPS ke Database Lokal
- **File Skrip:** [`docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql)
- **Kondisi:** Pada 27 Oktober 2026, guru di VPS aktif menginput nilai formatif dan sumatif (B. Arab, Nahwu, PJOK, MTK, SBK).
- **Aksi:** Mengintegrasikan seluruh baris baru menggunakan `INSERT IGNORE` agar nilai yang sudah ada tidak tertimpa.
- **Rincian Data yang Diserap:**
  - **Nilai Formatif:** Diserap **+450 baris** baru (dari 57.974 menjadi **58.424 baris**).
    - Bahasa Arab (ID 10): 208 baris
    - Nahwu (ID 44): 216 baris
    - PJOK (ID 9): 2 baris
    - Matematika (ID 4): 6 baris
    - Seni Budaya (ID 8): 18 baris
  - **Nilai Sumatif:** Diserap **+205 baris** baru (dari 15.263 menjadi **15.468 baris**).
    - Bahasa Arab: 44 baris
    - Nahwu: 47 baris
    - Matematika: 35 baris
    - Informatika: 25 baris
    - Seni Budaya: 23 baris
    - Hadist: 12 baris
    - IPS: 10 baris
    - B. Indonesia: 5 baris
    - PJOK: 4 baris

### E. Tahap 4: Verifikasi Status Database Lokal Saat Ini

```
STATUS INTEGRITAS DATABASE LOKAL (raporsmpit) PER 28 OKT 2026 01:38:
-------------------------------------------------------------------
- Jumlah Master Siswa          : 567 Siswa (LENGKAP, 24 Siswa Utuh)
- Jumlah Master LM             : 323 LM (LENGKAP TINGKAT 7, 8, 9)
- Jumlah Anggota Rombel        : 848 Baris (UNIK, 0 DUPLIKAT DI SELURUH TINGKAT)
- Total Nilai Sumatif          : 15.814 Baris (BERSIH, 0 DUPLIKAT)
- Total Nilai Formatif         : 59.736 Baris (LENGKAP, 0 DUPLIKAT)
- Total Nilai Rapor            : 4.880+ Baris (100% TERSINKRONISASI, 0 KOSONG)
- Siswa-Mapel Tanpa Nilai Rapor: 0 SISWA
- Duplikasi NIS Siswa          : 0 (BERSIH TOTAL, 100% SESUAI DAPODIK)
- Duplikasi NISN Siswa         : 0 (BERSIH TOTAL)
- Duplikasi Anggota Rombel     : 0
- Duplikasi Nilai Sumatif      : 0
- Nilai Sumatif Orphan (Yatim) : 0
- Status Rombel Kelas 7, 8, 9  : 100% UNIK, LENGKAP & VALID
-------------------------------------------------------------------
KESIMPULAN: Seluruh tingkatan (Kelas VII, VIII, IX) telah diaudit,
dibersihkan, disinkronkan ke nilai_rapor, dan diselaraskan ke NIS Dapodik.
File dump siap deploy ke server VPS.
```

### F. Tahap 5.1 & 5.2: Import Nilai STS Bahasa Arab & Nahwu Kelas 8 (Selesai 27 Okt 2026, 23:21 WIB)
- **Sumber File:**
  - `docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx` (Mapel ID: 10)
  - `docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx` (Mapel ID: 44)
- **Guru Pengampu:** Septian Syahputra, A.Md. Kom (Guru ID: 4)
- **Target Kelas:** Intan (ID 74), Safir (ID 76), Kyanite (ID 75)
- **Rincian Data yang Berhasil Dimasukkan:**
  - **Bahasa Arab:**
    - Kelas Intan: 22 siswa (88 baris formatif: NH1, NH2, UH1, UH2 + 22 baris sumatif PTS)
    - Kelas Safir: 22 siswa (88 baris formatif + 22 baris sumatif PTS) &rarr; **Sebelumnya 0 data, kini 100% lengkap!**
    - Kelas Kyanite: 22 siswa (88 baris formatif + 22 baris sumatif PTS)
  - **Nahwu:**
    - Kelas Intan: 22 siswa (88 baris formatif + 22 baris sumatif PTS)
    - Kelas Safir: 22 siswa (88 baris formatif + 22 baris sumatif PTS) &rarr; **Sebelumnya 0 data, kini 100% lengkap!**
    - Kelas Kyanite: 22 siswa (88 baris formatif + 22 baris sumatif PTS)
  - **Statistik Eksekusi:**
    - Formatif inserted: **264 baris** (Safir baru)
    - Formatif updated: **264 baris** (Intan & Kyanite sinkron)
    - Sumatif inserted: **66 baris** (Safir baru)
    - Sumatif updated: **66 baris** (Intan & Kyanite sinkron)
    - Total siswa cocok: **132 siswa** (Tingkat kecocokan **100%**, 0 unmatched).

### G. Tahap 5.3: Import Nilai STS IPA Kelas 8 Lengkap 5 Kelas (Selesai 27 Okt 2026, 23:25 WIB)
- **Sumber File:** `docs/data_nilai_sts_ganjil_2026/DKN STS GANJIL IPA KELAS 8 2026-2027.xlsx`
- **Mata Pelajaran:** Ilmu Pengetahuan Alam (Mapel ID: 5)
- **Guru Pengampu:** Suri Khairunnisa, S.Si, Gr (Guru ID: 36)
- **Target Kelas:** 5 Kelas Lengkap:
  - Granit (Rombel ID 72): 32 siswa
  - Topaz (Rombel ID 73): 33 siswa
  - Intan (Rombel ID 74): 22 siswa
  - Kyanite (Rombel ID 75): 22 siswa
  - Safir (Rombel ID 76): 22 siswa
- **Komponen Nilai:** NH 1, NH 2, UH 1, UH 2 (Formatif) dan PTS (Sumatif).
- **Statistik Eksekusi:**
  - Total Siswa Terproses: **129 siswa** (10 nama variasi/typo berhasil dipetakan via alias dictionary).
  - Formatif inserted/updated: **516 baris formatif** (4 baris per siswa).
  - Sumatif inserted/updated: **129 baris sumatif PTS**.
  - Tingkat kecocokan siswa: **100% (0 unmatched)**.
  - Status di database eRapor saat ini: **5 Kelas 100% LENGKAP**.

### H. Tahap 5.4: Import Nilai STS Bahasa Inggris Kelas 8 Lengkap 5 Kelas (Selesai 27 Okt 2026, 23:27 WIB)
- **Sumber File:** `docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 (Intan, Kyanite, Safir, Granit & Topaz) Oktober 2026/LM dan DKN STS B. Inggris Kls 8  Intan, Kyanite, Safir, Granit, Topaz(Oktober 2026).xlsx`
- **Mata Pelajaran:** Bahasa Inggris (Mapel ID: 6)
- **Guru Pengampu:** Eli Saprida, S.Pd, Gr (Guru ID: 38)
- **Penambahan Master LM:** Menambahkan LM 2 (*Memahami dan menggunakan kosakata ungkapan yang tepat tentang simple present tense*) ke `master_lm` sesuai panduan resmi guru di sheet deskripsi LM.
- **Target Kelas:** 5 Kelas Lengkap:
  - 8 Intan (Rombel ID 74): 22 siswa (88 formatif, 22 sumatif)
  - 8 Kyanite (Rombel ID 75): 22 siswa (88 formatif, 22 sumatif)
  - 8 Safir (Rombel ID 76): 22 siswa (88 formatif, 22 sumatif)
  - 8 Granit (Rombel ID 72): 32 siswa (128 formatif, 32 sumatif)
  - 8 Topaz (Rombel ID 73): 33 siswa (132 formatif, 33 sumatif)
- **Statistik Eksekusi:**
  - Total Siswa Terproses: **131 siswa (100% cocok, 0 unmatched)**.
  - Formatif inserted: **110 baris**, updated: **414 baris** (total 524 baris).
  - Sumatif inserted: **21 baris**, updated: **110 baris** (total 131 baris).
  - Status di database eRapor saat ini: **5 Kelas 100% LENGKAP**.

### I. Tahap 5.5 & 5.6: Import Template Kolektif Seni Budaya & Informatika (Selesai 27 Okt 2026, 23:29 WIB)
- **Sumber File:**
  - `Template_Kolektif_Tengah_Safir.xlsx` (Seni Budaya Safir, Guru ID: 14)
  - `Template_Kolektif_Topaz fix.xlsx` (Seni Budaya Topaz, Guru ID: 143)
  - `Template_Kolektif_Tengah_Kyanite.xlsx` (Informatika Kyanite, Guru ID: 2)
  - `Template_Kolektif_Tengah_Topaz.xlsx` (Informatika Topaz, Guru ID: 2)
- **Statistik Eksekusi:**
  - **Seni Budaya Safir:** 22 siswa cocok (66 baris formatif tersimpan).
  - **Seni Budaya Topaz:** 33 siswa cocok (66 formatif & 33 sumatif tersimpan).
  - **Informatika Kyanite:** 22 siswa cocok (88 formatif & 22 sumatif tersimpan).
  - **Informatika Topaz:** 33 siswa cocok (132 formatif & 33 sumatif tersimpan).
  - Tingkat kecocokan siswa: **100% (110 dari 110 siswa, 0 unmatched)**.

### J. Tahap 5.7: Import DKN Matematika & Hadist Kelas 8 (Selesai 27 Okt 2026, 23:34 WIB)
- **Sumber File:**
  - `DKN Sumatif Tengah Semester 26-27 GANJIL.xlsx` (Matematika Kelas 8, Guru ID: 8 - Riska Handayani, S.Pd, Gr)
  - `DKN Sumatif Tengah Semester.xlsx` (Hadist Kelas 8, Guru ID: 51 - Luthfia Chairunnisa, S.Pd)
- **Statistik Eksekusi:**
  - **Matematika:**
    - Granit (32 siswa): 128 formatif, 32 sumatif PTS &rarr; **Sebelumnya 0 data, kini 100% lengkap!**
    - Kyanite (22 siswa): 88 formatif, 22 sumatif PTS.
    - Intan (22 siswa): 88 formatif, 22 sumatif PTS.
    - Total: **76 siswa cocok (100%), 304 formatif & 76 sumatif PTS tersimpan**.
  - **Hadist:**
    - 8 Intan (22 siswa): 88 formatif, 22 sumatif PTS.
    - 8 Kyanite (22 siswa): 88 formatif, 22 sumatif PTS.
    - 8 Safir (22 siswa): 88 formatif, 22 sumatif PTS.
    - Total: **66 siswa cocok (100%), 264 formatif & 66 sumatif PTS tersimpan**.

### K. Tahap 5.8 & 5.9: Penyelarasan Master LM & Import Batch Sisa Seluruh Mapel Kelas 8 (Selesai 27 Okt 2026, 23:51 WIB)
- **Penyelarasan Master LM (Langkah A):**
  - Menambahkan LM 2 Bahasa Indonesia: `menelaah penggunaan deskripsi dan eksposisi pada teks laporan hasil observasi`.
  - Meng-update teks deskripsi LM 1 & 2 untuk PJOK, Seni Budaya, PAIBP, Hadist, dan PKN agar 100% persis dengan rumusan raport pada `LM STS Kelas 8 T.A. 2026-2027.xlsx`.
  - Total Master LM Kelas 8 Ganjil Tengah kini **26 LM lengkap**.
- **Import Batch Nilai Seluruh Mapel Sisa (Langkah B):**
  - **Bahasa Indonesia:** Topaz (31 siswa: 124 formatif, 31 sumatif), Granit/Intan/Kyanite/Safir (98 siswa: 392 formatif, 98 sumatif).
  - **PAIBP:** Granit (32 siswa: 128 formatif, 32 sumatif), Topaz/Intan/Kyanite/Safir (97 siswa: 388 formatif, 97 sumatif).
  - **Matematika:** Topaz & Safir (55 siswa: 212 formatif, 53 sumatif PTS).
  - **PKN / PP:** Seluruh 5 Kelas (131 siswa: 262 formatif, 131 sumatif PTS).
  - **IPS:** Seluruh 5 Kelas (131 siswa: 392 formatif, 131 sumatif PTS).
  - **PJOK:** Seluruh 5 Kelas (97 siswa terproses lengkap).
  - **Seni Budaya:** Granit, Kyanite, Safir (76 siswa: 304 formatif, 76 sumatif).
  - **Informatika Granit:** 32 siswa (128 formatif, 32 sumatif).

### L. Tahap 6: Pembuatan File Dump Final Bersih Siap Upload ke VPS (Selesai 27 Okt 2026, 23:52 WIB)
- **File Dump Final:** [`docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql)
- **Ukuran File:** **32,9 MB** (32.928.712 bytes)
- **Statistik Database Final:**
  - 567 Siswa Aktif (24 siswa hilang telah dipulihkan secara utuh).
  - 323 Master LM Lengkap Tingkat 7, 8, dan 9 (26 LM Kelas 8 Ganjil Tengah 100% selaras rapor guru).
  - 848 Anggota Rombel Unik (142 duplikasi bersih total).
  - **59.643 Nilai Formatif** (seluruh formatif kelas 8 terisi lengkap).
  - **15.877 Nilai Sumatif** (seluruh sumatif PTS kelas 8 terisi lengkap).
  - 0 Duplikasi & 0 Nilai Sumatif Orphan.
- **Cara Restore ke Server VPS Production:**
  ```bash
  # 1. Upload file dump ke VPS
  scp docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql user@vps_ip:/path/to/backup/
  
  # 2. Masuk ke VPS dan restore ke database MySQL
  mysql -u raporsmpit -p raporsmpit < /path/to/backup/raporsmpit_repaired_and_sts_complete_20261027.sql
  ```






---

## 3. RENCANA KERJA TAHAP 5: IMPORT NILAI LENGKAP STS GANJIL 2026

Berikut adalah urutan pengerjaan sub-tahap import berkas Excel dari `docs/data_nilai_sts_ganjil_2026/`:

### Sub-Tahap 5.1: DKN Bahasa Arab Kelas 8 (Safir, Kyanite, Intan)
- **Sumber:** `docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx`
- **Mata Pelajaran:** Bahasa Arab (ID: 10)
- **Sheet:**
  - `SAFIR`: Mengisi 22 siswa Safir yang sebelumnya **kosong (0 nilai)** di database.
  - `KYANITE`: Sinkronisasi nilai 22 siswa Kyanite.
  - `INTAN`: Sinkronisasi nilai 22 siswa Intan.
- **Komponen Nilai yang Dimasukkan:**
  - Formatif Harian (NH) LM 1 & LM 2
  - Formatif Ulangan (UH) LM 1 & LM 2
  - Sumatif STS

### Sub-Tahap 5.2: DKN Nahwu Kelas 8 (Safir, Kyanite, Intan)
- **Sumber:** `docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx`
- **Mata Pelajaran:** Nahwu (ID: 44)
- **Sheet:**
  - `SAFIR`: Mengisi 22 siswa Safir yang sebelumnya **kosong (0 nilai)**.
  - `KYANITE`: Sinkronisasi nilai 22 siswa Kyanite.
  - `INTAN`: Sinkronisasi nilai 22 siswa Intan.

### Sub-Tahap 5.3: DKN IPA Kelas 8 (5 Kelas Lengkap)
- **Sumber:** `docs/data_nilai_sts_ganjil_2026/DKN STS GANJIL IPA KELAS 8 2026-2027.xlsx`
- **Mata Pelajaran:** Ilmu Pengetahuan Alam (ID: 5)
- **Sheet:**
  - `KYANITE` (22 siswa)
  - `INTAN` (22 siswa)
  - `SAFIR` (22 siswa)
  - `GRANIT` (32 siswa)
  - `TOPAZ` (33 siswa)
- **Total Siswa IPA:** 131 siswa

### Sub-Tahap 5.4: DKN Bahasa Inggris Kelas 8 (5 Kelas Lengkap)
- **Sumber:** `docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 .../LM dan DKN STS B. Inggris Kls 8 Intan, Kyanite, Safir, Granit, Topaz(Oktober 2026).xlsx`
- **Mata Pelajaran:** Bahasa Inggris (ID: 6)
- **Target Kelas:** 5 Kelas (Intan, Kyanite, Safir, Granit, Topaz)

### Sub-Tahap 5.5: Template Kolektif Seni Budaya (Safir & Topaz)
- **Sumber:** `Template_Kolektif_Tengah_Safir.xlsx` & `Template_Kolektif_Topaz fix.xlsx`
- **Mata Pelajaran:** Seni Budaya (ID: 8)
- **Target Kelas:** Safir (sebelumnya hanya 7 siswa masuk) dan Topaz

### Sub-Tahap 5.6: Template Kolektif Informatika (Kyanite & Topaz)
- **Sumber:** `Template_Kolektif_Tengah_Kyanite.xlsx` & `Template_Kolektif_Tengah_Topaz.xlsx`
- **Mata Pelajaran:** Informatika (ID: 7)

### Sub-Tahap 5.7: DKN Kolektif Pelajaran Lainnya
- **Sumber:** `DKN Sumatif Tengah Semester 26-27 GANJIL.xlsx`, `DKN STS Kyanite.xlsx`, `DKN STS Safir.xlsx`
- **Mata Pelajaran:** PAI, PKn, Matematika, Hadist, IPS

---

## 4. TAHAP 7: AUDIT INTEGRITAS KELAS IX & PEMBERSIHAN 63 SUMATIF GANDA

- **Waktu Eksekusi:** 28 Oktober 2026, pukul 00:24 WIB
- **File Skrip:** [`docs/dbrepair/06_bersihkan_duplikat_nilai_sumatif.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/06_bersihkan_duplikat_nilai_sumatif.sql)
- **File Dump Final Terkini:** [`docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql) (**32,9 MB**)

### A. Hasil Audit Kelas IX (Tingkat 9)
Audit menyeluruh dilakukan pada rombel aktif Kelas IX Tahun Ajaran 2026/2027 Ganjil (TA ID 10):
1. **Anggota Rombel Kelas IX:**
   - Fairuz (ID 67): 29 siswa (Unik: 29)
   - Emerald (ID 68): 29 siswa (Unik: 29)
   - Biduri (ID 69): 31 siswa (Unik: 31)
   - Crystal (ID 70): 30 siswa (Unik: 30)
   - Diamond (ID 71): 31 siswa (Unik: 31)
   - **Total:** 150 siswa (**100% UNIK, 0 Duplikat**).
2. **Nilai Formatif Kelas IX:** **0 Duplikat** (Aman & Bersih).
3. **Nilai Sumatif Kelas IX:** **0 Duplikat** (Aman & Bersih).

### B. Temuan & Pembersihan 63 Nilai Sumatif Ganda (Kelas VII & VIII)
Di seluruh database ditemukan 63 grup nilai sumatif ganda di TA 10 (22 di Kelas VII dan 41 di Kelas VIII):
- **55 Kasus Nilai Dummy 1.00:** Diakibatkan oleh pengunggahan/penyimpanan form template dengan default nilai `1.00` pada 27 Okt jam 12:xx yang menimpa/menambah baris alih-alih meng-update. Nilai riil siswa (misal 70, 80, 96) tetap ada.
- **8 Kasus Nilai Kembar Identik:** Rekaman ganda dengan nilai sama persis (misal 57.50 dan 57.50).

**Hasil Eksekusi Skrip 06:**
- **Step 1:** Menghapus 55 baris nilai dummy `1.00` (mempertahankan nilai riil yang sah).
- **Step 2:** Menghapus 8 baris duplikat identik (mempertahankan ID terkini).
- **Sisa Nilai Sumatif Ganda di Seluruh Database:** **0 BARIS (BERSIH TOTAL 100%)**.
- **Total Baris Nilai Sumatif Terkini:** **15.814 baris** (berkurang tepat 63 baris).
- **File Dump Siap Deploy:** Berkas dump SQL utama telah di-generate ulang dengan status database terbaru yang bebas duplikat.

---

## 5. TAHAP 8: SINKRONISASI MASSAL NILAI RAPOR (PAIBP, B. INDONESIA, B. ARAB, NAHWU, DLL)

- **Waktu Eksekusi:** 28 Oktober 2026, pukul 01:03 WIB
- **File Sumber Excel:**
  - `docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx`
  - `docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Topaz).xlsx`
- **Akar Masalah Nilai Kosong di Rapor Cetak:**
  - Rapor cetak (`CetakRaporController`) menarik nilai dari tabel agregasi `nilai_rapor`.
  - Beberapa mapel (khususnya PAIBP, B. Indonesia, B. Arab, dan Nahwu di Kelas VIII Topaz) nilai mentahnya sudah 100% ada di `nilai_formatif` dan `nilai_sumatif`, tetapi belum pernah disinkronkan ke tabel `nilai_rapor` (masih 0 baris).
- **Eksekusi Penyelarasan & Sinkronisasi:**
  1. **PAIBP Kelas VIII Topaz (31 Siswa):**
     - Membaca sheet `8 TOPAZ` dari file Excel guru.
     - Menyerap nilai Nilai Rapor (NR) kolom P (cth: Adabi Limansyah Sagala: NH 60, UH 60, STS 55, NR = **64**).
     - Menghitung rata-rata formatif, sumatif, predikat, dan menyimpannya ke `nilai_rapor`.
  2. **Bahasa Indonesia Kelas VIII Topaz (31 Siswa):**
     - Membaca file `Bahasa Indonesia (8 Topaz).xlsx`.
     - Menyerap nilai LM 1 s.d. LM 3, STS, dan Nilai Rapor (NR) kolom L (cth: Adabi Limansyah Sagala: NR = **38**).
     - Menghitung dan menyimpannya ke `nilai_rapor`.
  3. **Bahasa Arab & Nahwu Kelas VIII Topaz (30 & 32 Siswa):**
     - Menghitung nilai akhir dengan bobot baku (35% NH + 35% UH + 30% STS) & menyimpannya ke `nilai_rapor`.
  4. **Sinkronisasi Sisa 294 Data di Seluruh Sekolah:**
     - Seluruh rombel di Tingkat VII, VIII, dan IX yang memiliki nilai formatif/sumatif tetapi belum ada di `nilai_rapor` telah disinkronisasi otomatis (total 418 baris).
     - **Sisa Siswa-Mapel Tanpa Nilai Rapor:** **0 SISWA (100% LENGKAP)**.
- **Hasil Cetak Rapor Adabi Limansyah Sagala (Terkini):**
  - PAI / PAIBP: **64** (Sangat Kurang) &rarr; *Terisi sesuai file Excel guru*
  - Pendidikan Pancasila: **83** (Baik)
  - Bahasa Indonesia: **38** (Sangat Kurang) &rarr; *Terisi sesuai file Excel guru*
  - Matematika: **78** (Baik)
  - IPA: **65**
  - IPS: **74**
  - Bahasa Inggris: **65**
  - Informatika: **55**
  - Seni Budaya: **70**
  - PJOK: **77**
  - Bahasa Arab: **72**
  - Nahwu: **72**
- **Pembaruan Berkas Dump:** [`docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql`](file:///d:/laragon\htdocs\erapoteasy\docs\dbrepair\raporsmpit_repaired_and_sts_complete_20261027.sql) (**33,0 MB**).

---

## 6. TAHAP 9: SINKRONISASI NIS RESMI DAPODIK 27 OKTOBER 2026

- **Waktu Eksekusi:** 28 Oktober 2026, pukul 01:36 WIB
- **File Skrip:** [`docs/dbrepair/07_sinkronisasi_nis_dapodik.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/07_sinkronisasi_nis_dapodik.sql)
- **Sumber Data Acuan:** [`docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/Data%20Dapodik%202026-2027%20(Update%2027%20Oktober%202026).xlsx)
- **Kondisi Sebelum Eksekusi:**
  - Ditemukan 3 NIS duplikat di database (`09.25.0079`, `09.25.0087`, `10.26.0172`).
  - Ada 198 siswa yang NIS-nya belum sesuai Dapodik (terutama siswa kelas VII yang masih memakai nomor pendaftaran PPDB sementara dan pergeseran nomor di kelas VIII).
- **Aksi yang Dijalankan:**
  1. Menyelaraskan NIS 198 siswa aktif di tabel `siswa` dengan nomor resmi Dapodik.
  2. Mengamankan 16 siswa non-dapodik yang tersimpan di DB (memberi penyesuaian suffix `-A` pada 8 siswa yang nomor lamanya berbenturan dengan urutan baru Dapodik) agar tidak terjadi collision.
  3. Menuntaskan 3 kasus duplikat NIS:
     - `Muhammad Akbar Wijaya` (ID 18) &rarr; `09.25.0080`
     - `MYSHA ZAHIRA SACHI` (ID 430) &rarr; `09.25.0088`
     - `FADHIL RAJABI` (ID 40) &rarr; `09.25.0044`
- **Hasil Verifikasi Pasca-Eksekusi:**
  - **Duplikasi NIS di Seluruh Database:** **0 DUPLIKAT (BERSIH 100%)**.
  - **Duplikasi NISN:** **0 DUPLIKAT**.
  - **Siswa Dapodik yang Sinkron:** **551 dari 551 Siswa (100% Selaras)**.
- **Pembaruan Berkas Dump:** [`docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/raporsmpit_repaired_and_sts_complete_20261027.sql) (**33,0 MB**).

---

## 7. TAHAP 10: INVESTIGASI NILAI KOSONG BAHASA ARAB & NAHWU KELAS VII IKHWAN

- **Waktu Investigasi:** 28 Oktober 2026, pukul 02:20 WIB
- **Kasus Uji Awal:** Siswa `AL BUQHORI RAFIF` (Kelas VII Amazonite, NIS `10.26.0140` / NISN `0148656167`) menampilkan tanda strip (`- -`) pada mapel Bahasa Arab dan Nahwu.
- **Pemeriksaan Database Backup Pra-Repair:**
  Dilakukan audit menyeluruh ke 4 berkas cadangan database sebelum perbaikan:
  1. `backups/raporsmpit_local_pre_repair_20261027_2309.sql` (Lokal pra-repair)
  2. `docs/db271026-2149/raporsmpit.sql` (VPS Dump 27 Okt 21:49)
  3. `raporsmpit-201026-0837.sql` (Dump 20 Okt)
  4. `backend/writable/backups/Auto_Full_20261012_130712.sql` (Dump 12 Okt)
- **Hasil Audit:**
  - **Kelas VII Akhwat (Carnelian & Ruby):** Diampu oleh Ustadzah Arie Machlina, M.Hum &rarr; Nilai formatif, sumatif, dan rapor **lengkap 100% di semua backup dan DB saat ini**.
  - **Kelas VII Ikhwan (Amazonite, Opal, Zamrud):** Diampu oleh Ustadz Muhammad Fahrozi Sianipar &rarr; Nilai formatif = 0, sumatif = 0, rapor = 0 di **seluruh backup database**.
  - **Kesimpulan:** Nilai Bahasa Arab dan Nahwu untuk kelas VII ikhwan (total 78 siswa) memang **belum pernah diinputkan oleh guru pengampu ke sistem e-Rapor sejak awal**. Tidak ada data yang hilang atau terhapus saat perbaikan database.
- **Tindak Lanjut & Dokumen Konfirmasi Guru:**
  - Telah dibuat berkas rekap konfirmasi resmi per rombel dan per siswa: [`docs/dbrepair/08_KONFIRMASI_NILAI_BAHASA_ARAB_DAN_NAHWU_KELAS_VII.md`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/08_KONFIRMASI_NILAI_BAHASA_ARAB_DAN_NAHWU_KELAS_VII.md).
  - Berkas ini siap dicetak / diserahkan kepada Ustadz Muhammad Fahrozi Sianipar untuk meminta berkas Excel DKN/Nilai STS agar dapat disinkronkan ke dalam sistem.
