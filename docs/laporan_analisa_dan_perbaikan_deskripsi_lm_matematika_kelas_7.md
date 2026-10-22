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
### 2.4. Mengapa Materi Bilangan Rasional Sempat Tidak Masuk ke Deskripsi Rapor?
Wali kelas sempat melaporkan: *"Pak, deskripsinya gak sesuai di LM pak. Yg bilangan Rasional tidak masuk"*.
Setelah ditelusuri:
1. Pada tabel `nilai_formatif`, guru hanya menginput nilai untuk `pertemuan = 1` (karena pada template kolektif sebelum perbaikan tanggal 20 Oktober, kolom formatif yang terbentuk hanya 1 kolom NH 1 & UH 1).
2. Pada kode controller, pemetaan hanya memasukkan LM yang terdapat di `nilai_formatif`:
   ```php
   foreach ($formatifRows as $fr) {
       $pert = (int)$fr['pertemuan'];
       if (isset($lmByNum[$pert])) {
           $nilaiLM[] = array_merge($lmByNum[$pert], ...);
       }
   }
   ```
   Karena hanya pertemuan 1 yang ada, array `$nilaiLM` hanya berisi 1 elemen (LM 1 - Bilangan Bulat).
3. Kode fallback (`if (empty($nilaiLM))`) terlewati karena `$nilaiLM` tidak kosong.
4. Akibatnya, LM 2 (Bilangan Rasional) yang tidak memiliki record di `nilai_formatif` **sama sekali tidak dievaluasi dan tidak dicetak di rapor**, sehingga rapor hanya menampilkan 1 materi saja.

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

### 3.2. Evaluasi Akurat Berdasarkan Nilai LM yang Riil Diinput (Tidak Memaksa Data Gaib)
Sistem **tidak memaksakan penggabungan LM yang belum dinilai** dengan skor karangan/asumsi. Kebijakan sistem:
1. Deskripsi rapor secara jujur dan akurat merefleksikan materi yang **benar-benar telah dinilai** oleh guru di tabel `nilai_formatif`.
2. Jika guru baru menginput nilai formatif untuk LM 1 (Bilangan Bulat), maka deskripsi rapor hanya mencetak LM 1.
3. Hal ini berfungsi sebagai **kontrol kualitas dan indikator pengingat (*reminder*)** bagi wali kelas dan guru mata pelajaran bahwa nilai untuk **LM 2 (Bilangan Rasional) belum diinput ke dalam sistem**.
4. Begitu guru mengimpor nilai untuk LM 2 (melalui template kolektif yang sekarang sudah memuat kolom NH 1, NH 2, UH 1, UH 2), deskripsi Bilangan Rasional akan **otomatis muncul berdampingan dengan Bilangan Bulat** tanpa perlu rekayasa sistem.

### 3.3. Penyempurnaan Tata Bahasa Penggabungan Antar-LM (Jika Ada $\ge 2$ LM)
1. Menghilangkan titik sebelum kata penghubung (`rtrim($deskripsiFinal, " .")`).
2. Menghubungkan capaian yang setara dengan kata penghubung `" serta "` yang baku dan alami dalam bahasa Indonesia.
3. Menghubungkan capaian yang memerlukan bimbingan dengan `", namun "` tanpa ada duplikasi frasa.

### 3.4. Penambahan Guard pada `BaseController.php`
Menambahkan pengaman `method_exists(service('request'), 'setLocale')` di `backend/app/Controllers/BaseController.php` agar pemanggilan controller melalui background worker/CLI (seperti `spark`) tidak mengalami fatal error.

---

## 4. Hasil Verifikasi dan Status Aktual

Pengujian dilakukan langsung terhadap data riil siswa:

### Kasus Siswa 1: Muhammad Faith Habib (Rombel VII Opal)
- **Kondisi Data:** Di database hanya ada nilai formatif `pertemuan = 1` (LM 1: Bilangan Bulat). Belum ada nilai untuk `pertemuan = 2`.
- **Hasil Cetak Rapor:**
  > *"Muhammad Faith Habib menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual."* ✅
- **Catatan:** Tidak menampilkan Bilangan Rasional karena nilai LM 2 memang **belum diinput oleh guru**.

### Simulasi Jika Guru Sudah Menginput LM 1 dan LM 2:
- Jika guru mengimpor nilai LM 2 (misal nilai 85.3), sistem otomatis menghasilkan deskripsi lengkap:
  > *"Muhammad Faith Habib menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan bulat serta menerapkan bilangan bulat dalam masalah kontekstual **serta menunjukkan penguasaan yang baik dalam membaca, menulis, membandingkan dan mengoperasikan bilangan rasional serta menerapkan bilangan rasional dalam masalah kontekstual.**"* ✅

### Kasus Siswa 2: Afifah Dhia Fadillah (Rombel VIII Intan - Kelas VIII)
- **Kondisi Data:** Guru sudah menginput nilai untuk Pertemuan 1 dan Pertemuan 2.
- **Hasil Cetak Rapor:**
  > *"Afifah Dhia Fadillah menunjukkan penguasaan yang baik dalam peserta didik dapat menyederhanakan bentuk aljabar atau angka yang memuat operasi bilangan berpangkat menggunakan sifat-sifatnya serta menunjukkan penguasaan yang baik dalam peserta didik dapat menjelaskan pengertian bilangan berpangkat sebagai bentuk perkalian berulang."* ✅ *(Kedua materi otomatis tercetak lengkap karena kedua nilai sudah diinput)*

---

## 5. Ringkasan File yang Diubah

1. **Database `master_lm`:**
   - Memperbarui baris ID 126 dan 127 sesuai capaian pembelajaran kontekstual yang diajarkan.
2. `backend/app/Controllers/WaliKelas/PreviewRaporController.php`:
   - Memperbaiki method `getDeskripsiDinamis()` agar memfilter semester aktif dan memetakan nilai formatif riil per pertemuan.
3. `backend/app/Controllers/Admin/CetakRaporController.php`:
   - Memperbaiki method `getDeskripsiDinamis()` agar memfilter semester aktif dan memetakan nilai formatif riil per pertemuan.
4. `backend/app/Controllers/BaseController.php`:
   - Menambahkan pengaman `method_exists()` untuk `setLocale()`.

