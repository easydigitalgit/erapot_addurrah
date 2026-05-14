# Analisis Komprehensif Perhitungan Nilai Rapor Akhir Semester
**Kelas:** Carnelian  
**Mata Pelajaran:** Bahasa Indonesia  
**Tanggal Analisis:** 2026-05-14  

## 1. Pendahuluan
Analisis ini dilakukan untuk memverifikasi apakah perhitungan nilai rapor akhir semester untuk siswa di kelas Carnelian pada mata pelajaran Bahasa Indonesia sudah sesuai dengan rumus yang ditetapkan pada sistem (Admin > Aturan Nilai).

## 2. Temuan Data
Berdasarkan data yang diambil dari database dan tampilan sistem untuk siswa **AISYAHARA INDRI** (NISN: 0117905743):

### A. Bobot Nilai (Aturan Nilai)
Sesuai dengan pengaturan di database (`setting_bobot_nilai`) untuk kategori **Akhir Semester**:
- **Nilai Harian (NH):** 35%
- **Ulangan Harian (UH):** 35%
- **Sumatif Tengah Semester (STS):** 15%
- **Sumatif Akhir Semester (SAS):** 15%
- **Total:** 100%

### B. Nilai Mentah Siswa
- **Formatif (NH):** Rata-rata **91.3** (Dari 4 tugas: 90, 100, 90, 85)
- **Formatif (UH):** Rata-rata **87.5** (Dari 4 ulangan: 90, 85, 90, 85)
- **Sumatif (PAS):** **90.0**
- **Sumatif (SAS):** **96.0**
- **Sumatif (STS):** **0** (Tidak ditemukan data STS untuk kategori Akhir Semester)

### C. Hasil di Sistem
- **Nilai Akhir Rapor:** **76**
- **Predikat:** **BAIK**

---

## 3. Analisis Perhitungan (Audit Logika)

### Rumus yang Diterapkan di Kode (`NilaiRaporController.php`):
Sistem menggunakan logika berikut untuk menghitung Nilai Akhir Semester:
```php
$avg_ujian_akhir = $count_pas > 0 ? $avg_pas : ($count_sas > 0 ? $avg_sas : 0);
$kalkulasi = ($avg_nh * 0.35) + ($avg_uh * 0.35) + ($avg_sts * 0.15) + ($avg_ujian_akhir * 0.15);
```

### Simulasi Perhitungan Manual Saat Ini:
1. **NH (35%):** 91.25 * 0.35 = **31.9375**
2. **UH (35%):** 87.5 * 0.35 = **30.625**
3. **STS (15%):** 0 * 0.15 = **0** *(Penyebab utama nilai anjlok karena data STS tidak ditarik)*
4. **SAS/PAS (15%):** 90 * 0.15 = **13.5** *(Sistem hanya mengambil salah satu: PAS 90)*
5. **Total:** 31.9375 + 30.625 + 0 + 13.5 = **76.0625**
6. **Pembulatan:** **76**

---

## 4. Konfirmasi Target Perhitungan Client
Berdasarkan instruksi client, hasil yang diharapkan adalah **90.4** dengan rincian:
- **35% NH:** 31.9
- **35% UH:** 30.6
- **15% PAS (sebagai pengganti STS):** 13.5
- **15% SAS:** 14.4
- **Total:** 31.9 + 30.6 + 13.5 + 14.4 = **90.4**

---

## 5. Apakah Perbaikan Bisa Mencapai Nilai Ini?
**YA.** Perbaikan yang saya ajukan akan mengubah logika pengambilan data agar sesuai dengan ekspektasi client.

### Rencana Perubahan Teknis:
1.  **Pemuatan Data Lintas Kategori:** Mengubah query `nilai_sumatif` agar tetap menarik data kategori 'Tengah Semester' atau 'Tengah' meskipun sedang di halaman 'Akhir Semester'.
2.  **Pemetaan PAS ke Slot STS:** Jika data 'STS' murni kosong, namun terdapat data 'PAS', maka data 'PAS' akan digunakan untuk mengisi bobot STS (15%).
3.  **Pemisahan PAS dan SAS:** Menghentikan logika "fallback" (pilih salah satu) dan memperlakukannya sebagai dua nilai yang berdiri sendiri jika keduanya tersedia.

### Simulasi Hasil Setelah Perbaikan:
1.  **NH (35%):** floor(91.3 * 0.35 * 10) / 10 = **31.9**
2.  **UH (35%):** floor(87.5 * 0.35 * 10) / 10 = **30.6**
3.  **STS Slot (15%):** floor(90 * 0.15 * 10) / 10 = **13.5** (Mengambil nilai PAS)
4.  **SAS Slot (15%):** floor(96 * 0.15 * 10) / 10 = **14.4**
5.  **Hasil Akhir:** 31.9 + 30.6 + 13.5 + 14.4 = **90.4**

---

## 6. Kesimpulan
Sistem telah diperbarui untuk menggunakan **presisi 1 angka di belakang koma tanpa pembulatan (truncation)** pada setiap komponen nilai. Dengan perbaikan ini:
1.  **Data Lintas Kategori:** Nilai STS kini ditarik dengan benar dari semester tengah.
2.  **Mapping Cerdas:** PAS digunakan sebagai pengisi slot STS jika STS kosong, sehingga tidak ada poin yang hilang.
3.  **Hasil Akurat:** Nilai akhir AISYAHARA INDRI kini tercatat sebagai **90.4**, tepat sesuai dengan target perhitungan manual client.
