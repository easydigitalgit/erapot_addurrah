# Analisis Root Cause Kesalahan Perhitungan Nilai Rapor Akhir Semester

Dokumen ini menganalisis kesalahan perhitungan nilai rapor Akhir Semester pada sistem E-Rapor Easy, menggunakan studi kasus siswa **AFIFAH DHIA FADILLAH** (Kelas **Intan**, Mapel **Bahasa Inggris**).

---

## 1. Ringkasan Kasus (Temuan UI vs Excel)

*   **Rumus yang Seharusnya (Aturan DB & Excel):**
    $$\text{Nilai Rapor} = (35\% \times \text{Rata NH}) + (35\% \times \text{Rata UH}) + (15\% \times \text{PAS}) + (15\% \times \text{SAS})$$
*   **Data Riil Siswa (dalam Database):**
    *   **Rata NH (Formatif Tugas):** $80.0$
    *   **Rata UH (Formatif Ulangan):** $79.67$ (tampil di UI sebagai $79.7$)
    *   **Nilai PAS (Sumatif Akhir):** $75.0$
    *   **Nilai SAS (Sumatif Akhir):** $79.14$ (tampil di UI sebagai $79.1$)
    *   **Nilai PTS/STS (Sumatif Tengah):** $62.0$
*   **Perbandingan Hasil:**
    *   **Hasil yang Diharapkan (Excel/Manual):** **$79$** (Tepatnya $78.8$ sebelum pembulatan)
    *   **Hasil yang Tampil di Sistem:** **$77$** (Tepatnya $76.8$ di database)

---

## 2. Analisis Root Cause (Penyebab Utama)

Berdasarkan investigasi kode program pada `backend/app/Controllers/GuruMapel/NilaiRaporController.php` (baik di fungsi `getData()` maupun `syncNilai()`), ditemukan **dua masalah utama**:

### Masalah 1: Kesalahan Pengambilan Komponen Sumatif (PTS digunakan alih-alih PAS)

Pada perhitungan Rapor Akhir Semester, sistem seharusnya mengabaikan nilai PTS/STS dan menggunakan nilai **PAS** dan **SAS**. Namun, kode backend menggunakan logika penentuan nilai sumatif sebagai berikut:

```php
// baris 279 & 509 pada NilaiRaporController.php
$val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;

// baris 282 & 510 pada NilaiRaporController.php
$val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);
```

**Alur Eksekusi pada Kasus Afifah Dhia Fadillah:**
1.  Karena Afifah memiliki nilai PTS/STS di tengah semester sebesar $62.0$, variabel `$count_sts > 0` bernilai `true`.
2.  Akibatnya, `$val_sts` diisi dengan nilai **PTS ($62.0$)** dan **BUKAN** nilai **PAS ($75.0$)**.
3.  Nilai SAS terdeteksi ada ($79.14$), sehingga `$val_sas` diisi dengan nilai SAS ($79.14$).
4.  Nilai PAS ($75.0$) **sama sekali tidak terpakai** dalam rumus akhir semester, melainkan digantikan oleh nilai PTS ($62.0$).

Hal ini menciptakan ketidaksesuaian besar karena kolom di UI dan cetak rapor melabeli kolom tersebut sebagai **Rata PAS**, namun sistem menghitungnya menggunakan nilai **PTS/STS** yang diperoleh siswa di tengah semester.

---

### Masalah 2: Presisi IEEE 754 Floating Point pada Truncation (Floor)

Sistem menerapkan pemotongan 1 desimal (*truncation/floor*) pada setiap komponen bobot nilai sebelum dijumlahkan:

```php
$c_nh  = floor(($avg_nh * $w_nh) * 10) / 10;
$c_uh  = floor(($avg_uh * $w_uh) * 10) / 10;
$c_sts = floor(($val_sts * $w_sts) * 10) / 10;
$c_sas = floor(($val_sas * $w_sas) * 10) / 10;
```

Ketika menghitung komponen STS (PTS) untuk Afifah:
*   Secara matematis: $62.0 \times 15\% = 9.3$
*   Namun, dalam representasi biner komputer (*floating point*), operasi `62 * 0.15` menghasilkan **`9.299999999999999`**.
*   Fungsi `floor(9.299999999999999 * 10) / 10` membulatkan nilai tersebut ke bawah menjadi **`9.2`** (bukan `9.3`). Ini mengurangi nilai akhir siswa sebesar $0.1$ secara tidak adil akibat *floating point precision error*.

---

## 3. Simulasi Perhitungan Matematis

Berikut adalah tabel simulasi perbedaan hasil perhitungan sistem (salah) vs formula yang seharusnya (benar):

| Komponen | Nilai Asli | Bobot | Perhitungan Sistem (Salah) | Perhitungan Seharusnya (Benar) |
| :--- | :---: | :---: | :--- | :--- |
| **Rata NH** | $80.00$ | $35\%$ | $\text{floor}(80 \times 0.35) = 28.0$ | $\text{floor}(80 \times 0.35) = 28.0$ |
| **Rata UH** | $79.67$ | $35\%$ | $\text{floor}(79.67 \times 0.35) = 27.8$ | $\text{floor}(79.67 \times 0.35) = 27.8$ |
| **PAS (STS)** | $75.00$ | $15\%$ | **PTS digunakan:** $\text{floor}(62 \times 0.15) = 9.2$ *(floating error)* | **PAS digunakan:** $\text{floor}(75 \times 0.15) = 11.2$ |
| **SAS** | $79.14$ | $15\%$ | $\text{floor}(79.14 \times 0.15) = 11.8$ | $\text{floor}(79.14 \times 0.15) = 11.8$ |
| **Total (Raw)** | | | **$76.8$** | **$78.8$** |
| **Rapor (Rounded)** | | | **$77$** (tampil di cetak rapor) | **$79$** (ekspektasi rapor) |

---

## 4. Solusi Perbaikan Kode

Untuk memperbaiki masalah ini secara menyeluruh di semua kelas dan mapel, kita perlu mengubah logika penentuan nilai sumatif di `NilaiRaporController.php` pada kategori **Akhir Semester** agar langsung memetakan `$val_sts` ke `$avg_pas` dan `$val_sas` ke `$avg_sas`.

### Rekomendasi Perubahan Kode (`backend/app/Controllers/GuruMapel/NilaiRaporController.php`):

Kita harus mengganti logika di dalam blok `else` pada fungsi `getData()` dan `syncNilai()`:

#### A. Pada Fungsi `getData()` (Baris ~273):
```diff
             } else {
                 $w_nh  = $bobot['akhir_semester']['nh'] / 100;
                 $w_uh  = $bobot['akhir_semester']['uh'] / 100;
                 $w_sts = $bobot['akhir_semester']['sts'] / 100;
                 $w_sas = $bobot['akhir_semester']['sas'] / 100;
 
-                $val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;
-                
-                // Jika PAS sudah dipakai di STS, jangan pakai lagi di SAS kecuali SAS kosong
-                $val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);
+                // Gunakan PAS untuk komponen STS, dan SAS untuk komponen SAS
+                $val_sts = $avg_pas;
+                $val_sas = $avg_sas;
                 
                 // KALKULASI DENGAN POTONG 1 DESIMAL (TRUNCATION) TIAP KOMPONEN SESUAI PERMINTAAN CLIENT
-                $c_nh  = floor(($avg_nh * $w_nh) * 10) / 10;
-                $c_uh  = floor(($avg_uh * $w_uh) * 10) / 10;
-                $c_sts = floor(($val_sts * $w_sts) * 10) / 10;
-                $c_sas = floor(($val_sas * $w_sas) * 10) / 10;
+                // Gunakan round(..., 4) sebelum floor untuk menghindari kesalahan presisi float IEEE 754
+                $c_nh  = floor(round($avg_nh * $w_nh, 4) * 10) / 10;
+                $c_uh  = floor(round($avg_uh * $w_uh, 4) * 10) / 10;
+                $c_sts = floor(round($val_sts * $w_sts, 4) * 10) / 10;
+                $c_sas = floor(round($val_sas * $w_sas, 4) * 10) / 10;
                 
                 $kalkulasi = $c_nh + $c_uh + $c_sts + $c_sas;
             }
```

#### B. Pada Fungsi `syncNilai()` (Baris ~502):
```diff
                 } else {
                     $w_nh  = $bobot['akhir_semester']['nh'] / 100;
                     $w_uh  = $bobot['akhir_semester']['uh'] / 100;
                     $w_sts = $bobot['akhir_semester']['sts'] / 100;
                     $w_sas = $bobot['akhir_semester']['sas'] / 100;
 
-                    // SMART MAPPING: Jika STS kosong, gunakan PAS sebagai pengganti
-                    $val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;
-                    $val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);
+                    // Gunakan PAS untuk komponen STS, dan SAS untuk komponen SAS
+                    $val_sts = $avg_pas;
+                    $val_sas = $avg_sas;
 
                     // KALKULASI DENGAN POTONG 1 DESIMAL (TRUNCATION) TIAP KOMPONEN SESUAI PERMINTAAN CLIENT
-                    $c_nh  = floor(($avg_nh * $w_nh) * 10) / 10;
-                    $c_uh  = floor(($avg_uh * $w_uh) * 10) / 10;
-                    $c_sts = floor(($val_sts * $w_sts) * 10) / 10;
-                    $c_sas = floor(($val_sas * $w_sas) * 10) / 10;
+                    // Gunakan round(..., 4) sebelum floor untuk menghindari kesalahan presisi float IEEE 754
+                    $c_nh  = floor(round($avg_nh * $w_nh, 4) * 10) / 10;
+                    $c_uh  = floor(round($avg_uh * $w_uh, 4) * 10) / 10;
+                    $c_sts = floor(round($val_sts * $w_sts, 4) * 10) / 10;
+                    $c_sas = floor(round($val_sas * $w_sas, 4) * 10) / 10;
 
                     $kalkulasi = $c_nh + $c_uh + $c_sts + $c_sas;
 
                     // Simpan rata-rata ke kolom DB (untuk archive)
                     $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
                     $rata_sumatif = round(($val_sts + $val_sas) / 2, 1);
                 }
```

---

## 5. Kesimpulan & Tindakan Lanjut

Kesalahan ini terjadi karena logika kode backend secara keliru menggunakan nilai **PTS/STS** sebagai pengganti komponen **PAS** ketika melakukan kalkulasi Akhir Semester, diperparah oleh kesalahan pembulatan desimal biner komputer (*IEEE 754 float precision*). 

Jika disetujui, kami dapat langsung mengaplikasikan perubahan kode ini ke dalam file `NilaiRaporController.php` agar perhitungan nilai rapor menjadi akurat di seluruh kelas dan siswa, kemudian guru mapel cukup melakukan sinkronisasi ulang.
