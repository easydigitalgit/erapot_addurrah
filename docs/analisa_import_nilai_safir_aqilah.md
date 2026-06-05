# Analisa Root Cause: Kegagalan Import Nilai Siswa (Aqilah Khanza Ramadhani) Kelas Safir

## Deskripsi Masalah
User melaporkan bahwa setelah mengimport file Excel Nilai Kolektif Kelas Safir (`Template_Kolektif_Tengah_Safir.xlsx`), nilai milik satu orang siswa atas nama **Aqilah Khanza Ramadhani** (NIS: `09.25.0019`) tidak masuk/tidak tampil di sistem.

## Hasil Investigasi
Berdasarkan pengecekan data di database dan file Excel template import, kami menemukan temuan-temuan berikut:

1. **Status Anggota Rombel**:
   - Di database, siswa **Aqilah Khanza Ramadhani** (Siswa ID: `88`) terdaftar secara sah di Rombel **Safir** (Rombel ID: `20`) untuk Tahun Ajaran `2025/2026` Semester `Genap` (Tahun Ajaran ID: `9`).
   
2. **Kondisi Data Nilai Formatif Aktual**:
   - Ditemukan data nilai formatif untuk mata pelajaran **Ilmu Pengetahuan Alam** (Mapel ID: `5`) atas nama siswa tersebut sudah ada di database sejak tanggal **04 Mei 2026**.
   - Namun, seluruh record nilai formatif tersebut tersimpan dengan **`rombel_id = 18`** (yang merupakan Rombel **Intan**), bukan `rombel_id = 20` (Safir).
   
3. **Penyebab Tidak Tampil di Halaman Safir**:
   - Halaman penilaian formatif kelas Safir memfilter data nilai berdasarkan `rombel_id = 20`. Karena nilai Aqilah tersimpan dengan `rombel_id = 18`, maka nilai tersebut tidak pernah ditarik/ditampilkan di halaman Safir.

4. **Akar Masalah (Root Cause) pada Kode Program**:
   - Logika import nilai kolektif terdapat di file [NilaiKolektifController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/GuruMapel/NilaiKolektifController.php#L369-L488).
   - Di dalam function `_saveFormatif()`, sistem memeriksa apakah nilai formatif siswa tersebut untuk pertemuan, mapel, dan tahun ajaran/semester yang bersangkutan sudah ada (`$exist`).
   - Jika data sudah ada, sistem akan melakukan **update** nilai menggunakan array `$dataSimpan`.
   - **Bug**: Di dalam array `$dataSimpan` untuk proses update, field `rombel_id` **tidak disertakan**:
     ```php
     $dataSimpan = [
         'nilai_angka'     => $nilai,
         'predikat'        => $predikat,
         'catatan'         => $catatan,
         'tahun_ajaran_id' => $ta_id,
         'semester'        => $sem
     ];
     ```
   - Akibatnya, meskipun file Excel yang diimport adalah kelas Safir (`rombel_id = 20`), record nilai formatif milik Aqilah tetap menyimpan `rombel_id = 18` (tidak ikut terupdate menjadi `20`).

---

## Rencana Perbaikan (Resolution Plan)

### 1. Perbaikan Kode Backend
Menambahkan field `'rombel_id' => $rombel_id` ke dalam array `$dataSimpan` pada function `_saveFormatif` di file [NilaiKolektifController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/GuruMapel/NilaiKolektifController.php#L461-L467) agar jika siswa dipindahkan rombelnya atau terdeteksi salah rombel, rombel pada nilai formatifnya ikut terupdate saat import ulang.

### 2. Patch Database (Data Correction)
Menjalankan query update langsung ke database untuk memperbaiki 4 record nilai formatif milik Aqilah Khanza Ramadhani yang salah rombel tersebut agar dipindahkan dari Rombel ID `18` (Intan) ke Rombel ID `20` (Safir).

---
**Status**: SELESAI (COMPLETED)  
**Dianalisa oleh**: Antigravity AI  
**Tanggal**: 05 Juni 2026
