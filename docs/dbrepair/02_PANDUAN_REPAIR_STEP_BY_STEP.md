# PANDUAN REPAIR DATABASE STEP-BY-STEP (LOKAL & VPS)

**Tujuan:** Memperbaiki kerusakan database (24 siswa hilang, 54 LM hilang, 142 duplikasi anggota rombel, dan 1.789 nilai sumatif terhapus), mengintegrasikan nilai baru yang diinput guru pada 27 Oktober 2026, dan menyiapkan database sebelum proses import nilai lengkap dari `docs/data_nilai_sts_ganjil_2026/`.

---

## DAFTAR FILE SQL REPAIR (DALAM FOLDER `docs/dbrepair/`)

| File SQL | Target Database | Fungsi Utama |
|---|---|---|
| [`01_restore_24_siswa.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/01_restore_24_siswa.sql) | VPS (`raporsmpit`) | Mengembalikan 24 siswa aktif di 6 kelas beserta seluruh biodata, NIS, NISN, NIK, dan nomor KK. |
| [`02_bersihkan_duplikat_anggota_rombel.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql) | VPS & Lokal | Membersihkan 142 baris duplikat di `anggota_rombel` dan mensinkronkan `siswa.rombel_id`. |
| [`03_sinkronisasi_master_lm.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/03_sinkronisasi_master_lm.sql) | VPS & Lokal | Mengembalikan 54 LM tingkat 8 & 9 yang hilang di VPS dan menyerap 3 LM baru inputan guru VPS ke Lokal. |
| [`04_restore_nilai_sumatif_terhapus.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql) | VPS (`raporsmpit`) | Mengembalikan 1.789 baris nilai sumatif yang musnah akibat foreign key cascade saat siswa terhapus. |
| [`05_sinkronisasi_nilai_guru_vps_ke_lokal.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql) | Lokal (`raporsmpit`) | Menyerap 450 nilai formatif dan 205 nilai sumatif baru yang diinput guru di VPS per 27 Oktober 2026. |

---

## LANGKAH-LANGKAH PENGERJAAN BERURUTAN (STEP BY STEP)

### LANGKAH 1: SAFETY SNAPSHOT & BACKUP
Sebelum menjalankan query apa pun, buat backup pengaman database saat ini.
- **Di Server VPS:**
  ```bash
  mysqldump -u raporsmpit -p raporsmpit > /backup/pre_repair_vps_$(date +%Y%m%d_%H%M%S).sql
  ```
- **Di Komputer Lokal (Laragon):**
  ```bash
  mysqldump -u root raporsmpit > d:/laragon/htdocs/erapoteasy/backups/pre_repair_lokal.sql
  ```

---

### LANGKAH 2: RESTORE 24 DATA SISWA HILANG DI VPS
File: [`docs/dbrepair/01_restore_24_siswa.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/01_restore_24_siswa.sql)
- **Tujuan:** Menghidupkan kembali 24 master siswa di tabel `siswa` pada VPS.
- **Efek Langsung:** 
  - **3.376 nilai formatif** dan **1.139 nilai rapor** yang sebelumnya berstatus *orphan* (mengambang tanpa master siswa) akan **langsung terhubung kembali secara otomatis** dengan ID siswa aslinya.
  - Form pencarian, cetak rapor, dan ledger wali kelas akan kembali menampilkan 24 siswa tersebut secara lengkap.
- **Perintah Eksekusi:**
  ```bash
  mysql -u [USER] -p[PASS] [DATABASE] < docs/dbrepair/01_restore_24_siswa.sql
  ```
- **Kueri Verifikasi:**
  ```sql
  SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif';
  -- Target: 567 siswa
  ```

---

### LANGKAH 3: PEMBERSIHAN 142 DUPLIKASI ANGGOTA ROMBEL
File: [`docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql)
- **Tujuan:** Menghapus entri ganda pada tabel `anggota_rombel` (terutama kelas Ruby, Carnelian, Opal, Topaz, Zamrud, Kyanite).
- **Efek Langsung:** 
  - Saat guru mendownload Template Kolektif Excel nilai STS, nama siswa tidak akan lagi muncul dua kali berturut-turut.
  - Total baris `anggota_rombel` akan berkurang tepat 142 baris (dari 990 menjadi 848 baris).
- **Perintah Eksekusi:**
  ```bash
  mysql -u [USER] -p[PASS] [DATABASE] < docs/dbrepair/02_bersihkan_duplikat_anggota_rombel.sql
  ```
- **Kueri Verifikasi:**
  ```sql
  SELECT rombel_id, siswa_id, COUNT(*) as cnt 
  FROM anggota_rombel 
  GROUP BY rombel_id, siswa_id 
  HAVING cnt > 1;
  -- Target: 0 baris (tidak ada lagi duplikat)
  ```

---

### LANGKAH 4: RESTORASI & SINKRONISASI MASTER LINGKUP MATERI (LM)
File: [`docs/dbrepair/03_sinkronisasi_master_lm.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/03_sinkronisasi_master_lm.sql)
- **Tujuan:** 
  1. Mengembalikan 54 LM tingkat 8 dan tingkat 9 (PAI, PKn, B. Indonesia, Matematika, IPA, B. Inggris, IPS, PJOK, Informatika, Seni Budaya, Hadist, dll.) yang hilang di VPS akibat bug regex angka romawi.
  2. Mempertahankan 3 LM Bahasa Inggris tingkat 9 yang baru diinput guru di VPS.
- **Efek Langsung:**
  - Form input nilai sumatif dan formatif guru kelas 8 & 9 di VPS akan kembali menampilkan daftar materi yang lengkap.
  - Jumlah master LM di VPS akan bertambah dari 267 menjadi 321 LM.
- **Perintah Eksekusi:**
  ```bash
  mysql -u [USER] -p[PASS] [DATABASE] < docs/dbrepair/03_sinkronisasi_master_lm.sql
  ```
- **Kueri Verifikasi:**
  ```sql
  SELECT tingkat, semester, kategori, COUNT(*) 
  FROM master_lm 
  GROUP BY tingkat, semester, kategori 
  ORDER BY tingkat, semester, kategori;
  -- Target: Tingkat 8 Ganjil Tengah >= 24 LM, Tingkat 9 Ganjil Tengah >= 17 LM
  ```

---

### LANGKAH 5: RESTORE NILAI SUMATIF YANG TERHAPUS
File: [`docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql)
- **Tujuan:** Memulihkan 1.789 baris nilai sumatif di VPS yang musnah terhapus secara cascade saat siswa terhapus (termasuk nilai semester berjalan dan riwayat semester sebelumnya).
- **Efek Langsung:**
  - Nilai sumatif 24 siswa kembali utuh tanpa kehilangan riwayat penilaian guru.
  - Menggunakan `INSERT IGNORE` sehingga nilai yang sudah ada di VPS tetap aman tidak tertimpa.
- **Perintah Eksekusi:**
  ```bash
  mysql -u [USER] -p[PASS] [DATABASE] < docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql
  ```
- **Kueri Verifikasi:**
  ```sql
  SELECT COUNT(*) FROM nilai_sumatif;
  -- Target di VPS: > 15.400 baris
  ```

---

### LANGKAH 6: SINKRONISASI NILAI BARU INPUTAN GURU DARI VPS KE LOKAL
File: [`docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql)
- **Tujuan:** Menyerap 450 nilai formatif baru dan 205 nilai sumatif baru yang telah diinput guru di server VPS (termasuk B. Arab, Nahwu, PJOK, MTK) ke database lokal.
- **Efek Langsung:**
  - Database lokal dan database VPS menjadi **100% identik dan mutakhir**.
- **Perintah Eksekusi:**
  ```bash
  mysql -u root raporsmpit < docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql
  ```

---

### LANGKAH 7: VERIFIKASI INTEGRITAS DATABASE (POST-REPAIR AUDIT)
Jalankan pengecekan integritas relasional:
```sql
-- 1. Cek apakah masih ada nilai formatif yatim (tanpa master siswa)
SELECT COUNT(*) FROM nilai_formatif nf 
LEFT JOIN siswa s ON nf.siswa_id = s.id 
WHERE s.id IS NULL;
-- Target: 0

-- 2. Cek apakah masih ada nilai sumatif yatim
SELECT COUNT(*) FROM nilai_sumatif ns 
LEFT JOIN siswa s ON ns.siswa_id = s.id 
WHERE s.id IS NULL;
-- Target: 0

-- 3. Cek apakah masih ada anggota rombel yatim
SELECT COUNT(*) FROM anggota_rombel ar 
LEFT JOIN siswa s ON ar.siswa_id = s.id 
WHERE s.id IS NULL;
-- Target: 0
```

---

### LANGKAH 8: IMPORT NILAI LENGKAP STS GANJIL DARI FOLDER `data_nilai_sts_ganjil_2026/`
Setelah database kembali bersih dan terstruktur:
1. Master LM Kelas 8 diselaraskan dengan referensi [`LM STS Kelas 8 T.A. 2026-2027.xlsx`](file:///d:/laragon/htdocs/erapoteasy/docs/data_nilai_sts_ganjil_2026/LM%20STS%20Kelas%208%20T.A.%202026-2027.xlsx).
2. Nilai DKN Bahasa Arab & Nahwu diimport dari:
   - `DKN BAHASA ARAB VIII STS Ganjil.xlsx`
   - `DKN NAHWU VIII STS Ganjil.xlsx`
3. Nilai DKN IPA diimport dari `DKN STS GANJIL IPA KELAS 8 2026-2027.xlsx`.
4. Nilai STS Kyanite, Safir, dan Topaz diimport melalui template kolektif yang telah disiapkan (`Template_Kolektif_Topaz fix.xlsx`, dll).
5. Lakukan sinkronisasi nilai akhir rapor melalui modul Wali Kelas.

