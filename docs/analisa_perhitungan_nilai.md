# Analisa Sistem Perhitungan Nilai Rata-Rata (E-Rapot Easy)

Dokumen ini menjelaskan logika teknis perhitungan nilai rata-rata harian (NH) dan nilai rata-rata ulangan (UH) dalam sistem E-Rapot Easy, serta menjawab kasus spesifik mengenai pembagi nilai (divisor).

## 1. Mekanisme Pembagi Dinamis (Dynamic Divisor)

Sistem menggunakan algoritma yang disebut **"Smart Algorithm: Pembagi Dinamis"**. Logika ini dirancang agar nilai siswa tetap adil meskipun guru belum menyelesaikan seluruh materi yang direncanakan.

### Logika Teknis:
Sistem tidak membagi nilai dengan jumlah total LM (Lingkup Materi) yang direncanakan di awal tahun, melainkan berdasarkan **progres nyata** di kelas tersebut.

*   **Langkah 1**: Sistem mencari nilai `pertemuan` (meeting/LM) tertinggi yang sudah pernah diinput oleh guru untuk mata pelajaran dan kelas tersebut.
*   **Langkah 2**: Nilai `max_pertemuan` tersebut dijadikan sebagai **pembagi (divisor)**.

### Contoh Kasus (Bahasa Arab):
*   **Rencana**: 4 LM.
*   **Kondisi**: Guru baru menginput nilai untuk 2 LM (LM 1 dan LM 2).
*   **Hasil**: Pembagi yang digunakan adalah **2**.
*   **Rumus**: `(Nilai LM 1 + Nilai LM 2) / 2`.

> [!TIP]
> **Mengapa demikian?**
> Jika dibagi 4, maka siswa yang mendapatkan nilai 100 di LM 1 dan LM 2 akan memiliki rata-rata `(100+100+0+0) / 4 = 50`. Dengan pembagi dinamis, nilainya tetap `(100+100) / 2 = 100`. Ini mencegah "penalti" bagi siswa akibat keterlambatan input atau progres materi yang belum selesai.

---

## 2. Pemisahan Jenis Nilai Rata-Rata

Sistem membedakan dua jenis rata-rata utama dalam kategori Formatif:

1.  **Rata-rata Harian (NH)**:
    *   Diambil dari nilai dengan jenis penilaian selain "UH" atau "ULANGAN" (biasanya jenis "Tugas").
    *   Pembaginya adalah `max_pertemuan` dari kategori non-UH.
2.  **Rata-rata Ulangan (UH)**:
    *   Diambil dari nilai dengan jenis penilaian yang mengandung kata "UH" atau "ULANGAN".
    *   Pembaginya adalah `max_pertemuan` dari kategori UH.

---

## 3. Rumus Perhitungan Akhir (Nilai Rapor)

Nilai rata-rata tersebut kemudian digabungkan dengan nilai Sumatif (Tengah Semester/STS dan Akhir Semester/SAS) menggunakan bobot yang dapat diatur di menu **Setting Bobot Nilai**.

### Contoh Bobot Default:
| Kategori | Sub-Kategori | Bobot |
| :--- | :--- | :--- |
| Akhir Semester | Rata-rata Harian (NH) | 30% |
| Akhir Semester | Rata-rata Ulangan (UH) | 30% |
| Akhir Semester | Sumatif Tengah Semester (STS) | 15% |
| Akhir Semester | Sumatif Akhir Semester (SAS/PAS) | 25% |

**Rumus Akhir:**
`Nilai Akhir = (Avg_NH * 30%) + (Avg_UH * 30%) + (STS * 15%) + (SAS * 25%)`

---

## 4. Lokasi Kode Sumber
Logika perhitungan ini dapat ditemukan di:
*   **Controller**: `backend\app\Controllers\GuruMapel\NilaiRaporController.php`
*   **Fungsi Utama**: `_getPembagiDinamis($formatifs)` dan `getData()`

---

## Kesimpulan
Sistem menghitung rata-rata berdasarkan **jumlah materi yang sudah ada nilainya (progress-based)**, bukan berdasarkan total rencana materi di awal. Jadi dalam kasus Bahasa Arab dengan 4 LM yang baru terisi 2, **pembaginya adalah 2**.
