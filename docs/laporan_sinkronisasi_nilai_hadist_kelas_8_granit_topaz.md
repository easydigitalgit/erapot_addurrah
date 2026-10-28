# LAPORAN SINKRONISASI NILAI HADIST KELAS VIII (GRANIT & TOPAZ)

- **Tanggal Eksekusi:** 28 Oktober 2026  
- **Berkas Sumber Excel:** [`docs/data_nilai_sts_ganjil_2026/(New) DKN & LM STS Kelas 8 T.A. 2026-2027/DKN & LM STS Kelas 8 T.A. 2026-2027/hadist (8 Granit, 8 Topaz).xlsx`](file:///d:/laragon/htdocs/erapoteasy/docs/data_nilai_sts_ganjil_2026/%28New%29%20DKN%20%26%20LM%20STS%20Kelas%208%20T.A.%202026-2027/DKN%20%26%20LM%20STS%20Kelas%208%20T.A.%202026-2027/hadist%20(8%20Granit,%208%20Topaz).xlsx)  
- **Mata Pelajaran:** Hadist (ID: `11`, Kode: `HDS`, KKM: `75`)  
- **Guru Pengampu:** Ustadz Yasir Fauzan, S.Pd.I (`guru_id = 50`)  
- **Tahun Ajaran:** 2026/2027 Ganjil (ID: `10`)  
- **Kategori Penilaian:** Tengah Semester (STS)  

---

## 1. RINGKASAN EKSEKUSI

Seluruh data nilai formatif, sumatif, dan nilai rapor untuk mata pelajaran **Hadist** pada kelas **VIII Granit** dan **VIII Topaz** telah berhasil disinkronkan dan disesuaikan secara transaksional (*ACID Committed*) ke dalam database:

| Kelas Rombel | ID Rombel | Jml Siswa di Excel | Formatif Di-upsert (NH1, NH2, UH1, UH2) | Sumatif PTS Di-upsert | Nilai Rapor Di-kalkulasi | Status |
|:------------|:---------:|:------------------:|:---------------------------------------:|:---------------------:|:------------------------:|:-------|
| **VIII Granit** | 72 | 32 Siswa | 128 Rekor | 32 Rekor | 32 Rekor | **Sukses 100%** |
| **VIII Topaz**  | 73 | 33 Siswa | 124 Rekor *(31 siswa)* | 31 Rekor *(31 siswa)* | 31 Rekor *(31 siswa)* | **Sukses 100%** *(2 siswa kosong)* |
| **TOTAL** | | **65 Siswa** | **252 Nilai Formatif** | **63 Nilai Sumatif** | **63 Nilai Rapor** | **Sinkron Sempurna** |

---

## 2. DETAIL SINKRONISASI PER KELAS

### A. Kelas VIII Granit (Rombel ID: 72)
- **Total Siswa:** 32 siswa.
- **Nilai Formatif:** 32 siswa × 4 kolom (`NH 1`, `NH 2`, `UH 1`, `UH 2`) = **128 rekor** di tabel `nilai_formatif`.
- **Nilai Sumatif:** 32 nilai PTS di tabel `nilai_sumatif`.
- **Nilai Rapor:** 32 siswa dihitung nilai akhirnya berdasarkan bobot STS resmi (35% NH + 35% UH + 30% PTS) serta capaian kompetensi tertinggi/terendah dari `master_lm` di tabel `nilai_rapor`.

### B. Kelas VIII Topaz (Rombel ID: 73)
- **Total Siswa:** 33 siswa.
- **Kondisi Khusus di Excel:**
  - **31 siswa** memiliki nilai lengkap (`NH 1`, `NH 2`, `UH 1`, `UH 2`, `PTS`).
  - **2 siswa** memiliki nilai kosong di berkas Excel guru:
    1. `IQBAL AR ZAAKY` (NIS: `09.25.0057`, No. 16)
    2. `NAUFAL AL-KHALISH NAMORA NASUTION` (NIS: `09.25.0090`, No. 28)
- **Hasil:** 31 siswa berhasil diisi nilai formatif (124 rekor), nilai sumatif PTS (31 rekor), dan nilai rapor (31 rekor). Nilai 2 siswa yang kosong tetap dipertahankan kosong agar tidak menginput angka nol palsu.

---

## 3. FORMULA BOBOT & PREDIKAT YANG DIGUNAKAN

1. **Rata-rata Formatif:**
   $$\text{Rata NH} = \frac{\text{NH}_1 + \text{NH}_2}{2}, \quad \text{Rata UH} = \frac{\text{UH}_1 + \text{UH}_2}{2}$$
   $$\text{Rata-rata Formatif} = \text{round}\left(\frac{\text{Rata NH} + \text{Rata UH}}{2}, 1\right)$$

2. **Nilai Akhir Rapor Tengah Semester (STS):**
   $$\text{Nilai Akhir} = (\text{Rata NH} \times 0.35) + (\text{Rata UH} \times 0.35) + (\text{PTS} \times 0.30)$$

3. **Predikat Sesuai KKM 75:**
   - $\ge 90$: **Sangat Baik (A)**
   - $\ge 80$: **Baik (B)**
   - $\ge 75$: **Cukup (C)**
   - $< 75$: **Perlu Bimbingan (D)**

---

## 4. CONTOH SAMPEL HASIL SINKRONISASI DI DATABASE

```text
-- SAMPEL KELAS VIII GRANIT --
NIS: 09.25.0001 | Abid Zuhair        | Formatif: 82.5 | Sumatif: 80 | Akhir: 81.8 | Predikat: Baik
NIS: 09.25.0006 | AHMAD OKA AL FATIH | Formatif: 83.5 | Sumatif: 90 | Akhir: 85.4 | Predikat: Baik
NIS: 09.25.0008 | AHYA MAULIDAN      | Formatif: 81.5 | Sumatif: 75 | Akhir: 79.6 | Predikat: Baik

-- SAMPEL KELAS VIII TOPAZ --
NIS: 09.25.0002 | ADABI LIMANSYAH S. | Formatif: 77.5 | Sumatif: 75 | Akhir: 76.8 | Predikat: Baik
NIS: 09.25.0011 | ALBIZRA WALI S.    | Formatif: 82.0 | Sumatif: 75 | Akhir: 79.9 | Predikat: Baik
NIS: 09.25.0024 | ARZIKI ALI AZKA    | Formatif: 82.5 | Sumatif: 85 | Akhir: 83.3 | Predikat: Baik
```

