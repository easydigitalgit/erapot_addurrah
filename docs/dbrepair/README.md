# REPOSITORI REPAIR DATABASE ERAPOR (LOKAL VS VPS)

Folder ini berisi hasil komparasi mendalam antara Database Lokal dan Database VPS (dump per 27 Oktober 2026 pukul 21:49 WIB), analisa akar masalah, panduan eksekusi perbaikan bertahap (*step-by-step*), serta seluruh skrip SQL siap eksekusi.

---

## DAFTAR DOKUMEN & SKRIP

1. **[01_KOMPARASI_MENDALAM_DATABASE.md](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/01_KOMPARASI_MENDALAM_DATABASE.md)**  
   Laporan komparasi teknis per tabel, analisis hilangnya 24 siswa di 6 rombel, hilangnya 54 LM tingkat 8 & 9 di VPS, anomali 142 duplikasi di `anggota_rombel`, dan analisa dampak penghapusan cascade pada nilai sumatif & formatif.

2. **[02_PANDUAN_REPAIR_STEP_BY_STEP.md](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_PANDUAN_REPAIR_STEP_BY_STEP.md)**  
   Panduan operasional berurutan dari Tahap 1 (Backup) hingga Tahap 8 (Import Nilai STS Ganjil dari folder `data_nilai_sts_ganjil_2026`).

3. **[03_PROGRESS_DAN_LOG_EKSEKUSI_REPAIR.md](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/03_PROGRESS_DAN_LOG_EKSEKUSI_REPAIR.md)**  
   Buku catatan kerja (*living log*) yang merekam riwayat eksekusi, jumlah data sebelum dan sesudah secara presisi, serta status progres per tahapan.

4. **[04_KOMPARASI_NILAI_DAN_LM_KELAS8_LENGKAP.md](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/04_KOMPARASI_NILAI_DAN_LM_KELAS8_LENGKAP.md)**  
   Hasil komparasi mendalam seluruh 19 berkas DKN & LM resmi guru di folder `docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/` terhadap isi database saat ini (rincian mapel yang sudah sinkron 100% dan mapel yang masih kurang).

5. **Skrip SQL Perbaikan (Siap Eksekusi Berurutan):**
   - **[`01_restore_24_siswa.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/01_restore_24_siswa.sql):** Mengembalikan 24 record master siswa yang hilang di VPS sehingga 3.376 nilai formatif dan 1.139 nilai rapor orphan kembali terhubung.
   - **[`02_bersihkan_duplikat_anggota_rombel.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql):** Membersihkan 142 entri ganda pada tabel `anggota_rombel`.
   - **[`03_sinkronisasi_master_lm.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/03_sinkronisasi_master_lm.sql):** Mengembalikan 54 LM tingkat 8 & 9 yang hilang dan memelihara 3 LM baru inputan guru VPS.
   - **[`04_restore_nilai_sumatif_terhapus.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql):** Memulihkan 1.789 baris nilai sumatif yang musnah terhapus secara cascade.
   - **[`05_sinkronisasi_nilai_guru_vps_ke_lokal.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql):** Menyerap 450 nilai formatif baru dan 205 nilai sumatif baru dari VPS ke database lokal.

---

## TAHAPAN SELANJUTNYA SETELAH REPAIR

Setelah skrip di atas dieksekusi dan diverifikasi, kita akan langsung memproses import nilai lengkap STS Ganjil TA 2026/2027 dari folder:
[`docs/data_nilai_sts_ganjil_2026/`](file:///d:/laragon/htdocs/erapoteasy/docs/data_nilai_sts_ganjil_2026/)

