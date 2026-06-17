# Histori Perubahan Perhitungan Nilai Rapor

Dokumen ini mendokumentasikan perubahan perhitungan nilai rapor pada sistem E-Rapor Easy sebagai referensi historis bagi pengembang dan administrator.

---

## Informasi Perubahan

*   **Tanggal Perubahan:** 17 Juni 2026
*   **File yang Dimodifikasi:** `backend/app/Controllers/GuruMapel/NilaiRaporController.php`
*   **File Backup:** `backups/NilaiRaporController.php.bak`
*   **Tujuan Perubahan:** Memperbaiki kesalahan perhitungan Nilai Rapor Akhir Semester di mana nilai PTS (Tengah Semester) digunakan secara keliru menggantikan nilai PAS (Akhir Semester), serta menangani kesalahan presisi biner komputer (*floating point error*) pada operasi pemotongan desimal (*truncation/floor*).

---

## Detail Kesalahan Sebelum Perbaikan

### 1. Penggunaan PTS sebagai pengganti PAS
Pada kategori perhitungan **Akhir Semester**, program mengambil data sumatif untuk menghitung komponen bobot ketiga (`$w_sts` sebesar 15%) dan keempat (`$w_sas` sebesar 15%). Kode aslinya adalah:
```php
$val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;
$val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);
```
Logika ini secara keliru mendeteksi jika ada nilai PTS/STS (`$count_sts > 0`), maka `$val_sts` diisi dengan rata-rata PTS/STS. Hal ini mengakibatkan nilai PAS yang diinput oleh guru terabaikan, dan digantikan oleh nilai ujian tengah semester (PTS).

### 2. Kesalahan Pembulatan Biner IEEE 754 (Float Precision)
Operasi perkalian nilai pecahan seringkali menghasilkan presisi biner yang sedikit kurang dari nilai riil (contoh: `62.0 * 0.15 = 9.299999999999999` di memori komputer). Ketika dipotong dengan fungsi `floor()` untuk mengambil satu desimal:
```php
$c_sts = floor(($val_sts * $w_sts) * 10) / 10;
```
Hasilnya berubah dari `9.3` menjadi `9.2`, menyebabkan pengurangan nilai siswa secara keliru.

---

## Solusi & Perubahan Kode

Perubahan dilakukan di dua fungsi utama di `NilaiRaporController.php`, yaitu pada fungsi penayangan `getData()` dan fungsi penyimpanan `syncNilai()` pada bagian blok `else` (kategori **Akhir Semester**):

### Perubahan Kode:
```php
// SEBELUM
$val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;
$val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);

$c_nh  = floor(($avg_nh * $w_nh) * 10) / 10;
$c_uh  = floor(($avg_uh * $w_uh) * 10) / 10;
$c_sts = floor(($val_sts * $w_sts) * 10) / 10;
$c_sas = floor(($val_sas * $w_sas) * 10) / 10;

// SESUDAH
// Untuk Rapor Akhir Semester:
// - Komponen ke-3 (bobot 'sts') menggunakan nilai PAS (Penilaian Akhir Semester)
// - Komponen ke-4 (bobot 'sas') menggunakan nilai SAS (Sumatif Akhir Semester)
$val_sts = $avg_pas;
$val_sas = $avg_sas;

// KALKULASI DENGAN POTONG 1 DESIMAL (TRUNCATION) TIAP KOMPONEN SESUAI PERMINTAAN CLIENT
// Gunakan round(..., 4) sebelum floor untuk menghindari precision error biner float IEEE 754
$c_nh  = floor(round($avg_nh * $w_nh, 4) * 10) / 10;
$c_uh  = floor(round($avg_uh * $w_uh, 4) * 10) / 10;
$c_sts = floor(round($val_sts * $w_sts, 4) * 10) / 10;
$c_sas = floor(round($val_sas * $w_sas, 4) * 10) / 10;
```

---

## Verifikasi Hasil (Kasus Afifah Dhia Fadillah)

Setelah perbaikan ini diterapkan, simulasi perhitungan nilai rapor Afifah Dhia Fadillah (Kelas Intan, Mapel Bahasa Inggris) berjalan sesuai dengan formula yang diharapkan:

*   **Rata NH:** $80 \times 0.35 = 28.0$
*   **Rata UH:** $79.67 \times 0.35 = 27.8833$ (di-round & floor menjadi $27.8$)
*   **Rata PAS:** $75 \times 0.15 = 11.25$ (di-round & floor menjadi $11.2$)
*   **Rata SAS:** $79.14 \times 0.15 = 11.871$ (di-round & floor menjadi $11.8$)
*   **Nilai Akhir (Raw):** $28.0 + 27.8 + 11.2 + 11.8 = 78.8$
*   **Tampilan Cetak Rapor (Rounded):** **$79$** (dari `round(78.8)`)

---

## Instruksi Tambahan bagi Guru

Setelah kode program ini diperbarui:
1.  **Guru Mapel** perlu masuk ke menu **Kalkulasi & Sinkronisasi Nilai Rapor** untuk kelas-kelas terkait.
2.  Pilih **Rapor Akhir Semester** beserta kelas dan mapelnya.
3.  Klik tombol **Sinkronisasi Kelas Ini** untuk memperbarui data nilai di database sesuai dengan logika perhitungan yang baru.
4.  Setelah disinkronisasi, nilai rapor siswa di dashboard guru, dashboard wali kelas, dan cetakan rapor akan otomatis terbarui dengan hasil yang benar.
