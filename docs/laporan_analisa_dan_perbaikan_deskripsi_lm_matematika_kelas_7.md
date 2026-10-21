# Laporan Analisa dan Perbaikan Deskripsi LM Rapor Matematika Kelas VII

**Tanggal:** 21 Oktober 2026  
**Status:** Selesai / Teruji  
**Modul Terdampak:** Preview Rapor Wali Kelas, Cetak Rapor Admin (PDF), Master Lingkup Materi (LM)

---

## 1. Latar Belakang Masalah
Wali kelas VII (Rombel Opal) melaporkan adanya ketidaksesuaian capaian kompetensi / deskripsi rapor yang tercetak untuk mata pelajaran **Matematika (Tengah Semester Ganjil TA 2026/2027)**.

### Contoh Kasus Siswa:
- **Nama Siswa:** Ahmad Zaidan Bukhari Al Azmi (ID: 451, Kelas VII Opal)
- **Mata Pelajaran:** Matematika
- **Nilai Rapor:** 78 (Predikat: Baik)
- **Teks yang Tercetak di Rapor Sebelum Perbaikan:**
  > *"Ahmad Zaidan Bukhari Al Azmi cukup menguasai dalam membaca, menulis, membandingkan, dan menyelesaikan masalah yang berkaitan dengan bilangan bulat, namun perlu peningkatan pemahaman lebih lanjut., **namun cukup menguasai dalam menyelesaikan masalah yang berkaitan dengan persamaan dan pertidaksamaan linear satu variabel, namun perlu peningkatan pemahaman lebih lanjut.**"*

### Temuan Kendala:
1. Tercetak materi *"persamaan dan pertidaksamaan linear satu variabel"*, padahal materi tersebut adalah **materi Semester Genap**, bukan Semester Ganjil yang sedang berjalan.
2. Terdapat kalimat berulang dan rancu secara tata bahasa: `", namun perlu peningkatan pemahaman lebih lanjut., namun cukup menguasai dalam ..., namun perlu peningkatan pemahaman lebih lanjut."`.
3. Guru Mata Pelajaran / Wali Kelas mengonfirmasikan bahwa capaian materi (LM) Matematika Kelas 7 yang diajarkan pada tengah semester ganjil ini seharusnya adalah:
   - **LM 1:** *peserta didik dapat membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual*
   - **LM 2:** *peserta didik dapat membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual*

---

## 2. Hasil Investigasi dan Akar Masalah (Root Cause)

### 2.1. Bocornya Materi Semester Genap Akibat Tidak Ada Filter `semester`
Di file `PreviewRaporController.php` (line 700 & 718) dan `CetakRaporController.php` (line 695 & 714), method perakit teks capaian `getDeskripsiDinamis(...)` menerima parameter `$semester`, namun parameter tersebut **sama sekali tidak disertakan dalam query `master_lm`**:
```php
// KODE LAMA (BERMASALAH):
$allLm = $this->db->table('master_lm')
    ->where([
        'mapel_id' => $mapel_id, 
        'tingkat'  => $tingkatClean, 
        'kategori' => $kategoriDB
    ])
    ->orderBy('kode_lm', 'ASC')
    ->get()->getResultArray();
```
Karena tidak memfilter `semester = 'Ganjil'`, sistem mengambil materi dari Semester Ganjil dan Semester Genap sekaligus.
Di database untuk tingkat 7 kategori Tengah:
- **ID 126 (Ganjil):** LM 1 - Bilangan Bulat
- **ID 127 (Ganjil):** LM 2 - Bilangan Rasional
- **ID 309 (Genap):** LM 1 - Rasio
- **ID 310 (Genap):** LM 2 - Persamaan dan Pertidaksamaan Linear Satu Variabel

Data di atas disusun berurutan, sehingga baris terakhir adalah ID 310 (Semester Genap). Sistem menganggap ID 310 sebagai kompetensi terendah (`$worst`), lalu mencetaknya ke rapor Semester Ganjil.

### 2.2. Gagalnya Pencocokan Nilai Formatif ke Master LM (Pemicu Fallback)
Pada fungsi lama, sistem mencoba mencocokkan data `nilai_formatif` ke `master_lm` dengan cara:
```php
$kodeEntry = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)$rn[$fieldType]));
// Mencocokkan REPLACE(kode_lm, ' ', '') dengan $kodeEntry
```
Pada tabel `nilai_formatif`:
- `$fieldType` terdeteksi sebagai kolom `jenis_penilaian` yang berisi teks `'Tugas'` (Nilai Harian) atau `'Ulangan'` (Ulangan Harian).
- Sedangkan pada tabel `master_lm`, kolom `kode_lm` berisi `'LM 1'`, `'LM 2'`, dst.
- Perbandingan antara `'LM1'` dengan `'TUGAS'` atau `'ULANGAN'` **selalu menghasilkan 0 kecocokan (gagal)**.
- Akibatnya, sistem selalu jatuh ke blok *fallback* dan mengambil materi lintas semester secara salah.

### 2.3. Redaksi Master LM yang Perlu Diselaraskan
Pada tabel `master_lm`, baris data untuk Matematika Kelas VII Semester Ganjil Tengah (ID 126 & 127) masih memuat redaksi draf lama:
- **ID 126:** *"Membaca, menulis, membandingkan, dan menyelesaikan masalah yang berkaitan dengan bilangan bulat"*
- **ID 127:** *"Menyatakan dan menyelesaikan masalah yang berkaitan dengan bilangan bilangan rasional"* (terdapat pengulangan kata *bilangan bilangan*).

---

## 3. Implementasi Perbaikan

### 3.1. Pembaruan Data `master_lm` (Database)
Melakukan update pada baris ID 126 dan 127 agar sesuai dengan redaksi resmi dari guru/walikelas:

```sql
-- 1. LM 1 (ID: 126)
UPDATE master_lm SET 
    deskripsi_lm = 'Membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual',
    deskripsi_a  = 'Menunjukkan penguasaan yang sangat baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual.',
    deskripsi_b  = 'Menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual.',
    deskripsi_c  = 'Cukup menguasai dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual, namun perlu peningkatan pemahaman lebih lanjut.',
    deskripsi_d  = 'Perlu bimbingan dan pendampingan khusus dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual.',
    updated_at   = NOW()
WHERE id = 126;

-- 2. LM 2 (ID: 127)
UPDATE master_lm SET 
    deskripsi_lm = 'Membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual',
    deskripsi_a  = 'Menunjukkan penguasaan yang sangat baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual.',
    deskripsi_b  = 'Menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual.',
    deskripsi_c  = 'Cukup menguasai dalam membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual, namun perlu peningkatan pemahaman lebih lanjut.',
    deskripsi_d  = 'Perlu bimbingan dan pendampingan khusus dalam membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual.',
    updated_at   = NOW()
WHERE id = 127;
```

### 3.2. Perbaikan Kode Controller Rapor
Memperbarui method `getDeskripsiDinamis()` pada:
- `backend/app/Controllers/WaliKelas/PreviewRaporController.php`
- `backend/app/Controllers/Admin/CetakRaporController.php`

**Poin-poin Perubahan:**
1. **Filter Semester & Status Aktif:**
   Query `master_lm` kini mengunci semester aktif (`->where('semester', $semester)`) dan status aktif (`->where('status', 'Aktif')`), sehingga tidak akan ada materi semester lain yang masuk.
2. **Pencocokan Nilai Formatif Berdasarkan Kolom `pertemuan`:**
   Nilai formatif dikelompokkan berdasarkan nomor pertemuan (`pertemuan 1` untuk LM 1, `pertemuan 2` untuk LM 2). Rata-rata nilai formatif pada pertemuan tersebut dipetakan langsung ke LM yang bersesuaian.
3. **Penyempurnaan Tata Bahasa Kalimat Gabungan:**
   Jika nilai capaian tertinggi dan terendah bernilai sama (misal pada fallback nilai akhir), teks dihubungkan secara harmonis (`"serta"`) dan tidak menambahkan kata penghubung kontras `", namun"` yang berulang jika siswa tidak mengalami kendala.

### 3.3. Penambahan Guard pada `BaseController.php`
Menambahkan pengaman `method_exists(service('request'), 'setLocale')` di `backend/app/Controllers/BaseController.php` agar pemanggilan controller melalui background worker/CLI (seperti `spark`) tidak mengalami fatal error.

---

## 4. Hasil Verifikasi dan Pengujian

Pengujian dilakukan langsung terhadap data riil siswa:

### Kasus Siswa Kelas VII (Ahmad Zaidan Bukhari Al Azmi - Rombel VII Opal)
- **Nilai Formatif:** Pertemuan 1 (Tugas: 97, Ulangan: 80 $\rightarrow$ Rata-rata: 88.5 / Predikat B)
- **Teks Rapor Sebelum:**
  > *"Ahmad Zaidan Bukhari Al Azmi cukup menguasai dalam membaca, menulis, membandingkan, dan menyelesaikan masalah yang berkaitan dengan bilangan bulat, namun perlu peningkatan pemahaman lebih lanjut., **namun cukup menguasai dalam menyelesaikan masalah yang berkaitan dengan persamaan dan pertidaksamaan linear satu variabel, namun perlu peningkatan pemahaman lebih lanjut.**"* ❌
- **Teks Rapor Sesudah:**
  > *"Ahmad Zaidan Bukhari Al Azmi menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual."* ✅

### Kasus Siswa Kelas VIII (Afifah Dhia Fadillah - Rombel VIII Intan)
- **Nilai Formatif:** Pertemuan 1: 80, Pertemuan 2: 85 (Keduanya LM Ganjil Aktif)
- **Hasil:** Kedua materi ganjil berhasil dipetakan dengan tepat tanpa ada kebocoran materi semester genap.

---

## 5. Ringkasan File yang Diubah

1. **Database `master_lm`:**
   - Memperbarui baris ID 126 dan 127 sesuai capaian pembelajaran kontekstual yang diajarkan.
2. `backend/app/Controllers/WaliKelas/PreviewRaporController.php`:
   - Memperbaiki method `getDeskripsiDinamis()`.
3. `backend/app/Controllers/Admin/CetakRaporController.php`:
   - Memperbaiki method `getDeskripsiDinamis()`.
4. `backend/app/Controllers/BaseController.php`:
   - Menambahkan pengaman `method_exists()` untuk `setLocale()`.
