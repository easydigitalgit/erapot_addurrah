# Panduan Lengkap Fitur Penilaian Guru Mapel

Dokumen ini menjelaskan cara kerja 4 menu penilaian akademik guru mapel:

1. Nilai Kolektif: /guru/nilai-kolektif
2. Nilai Formatif: /guru/nilai-formatif
3. Nilai Sumatif: /guru/nilai-sumatif
4. Nilai Rapor: /guru/nilai-rapor

Fokus dokumen ini adalah alur praktik penggunaan oleh guru, lalu alur teknis di backend (endpoint, proses simpan, status data, dan sumber perhitungan).

## Ringkasan Arsitektur Penilaian

Secara umum, sistem menggunakan tabel utama berikut:

1. nilai_formatif: menyimpan nilai harian/tugas dan ulangan harian per pertemuan.
2. nilai_sumatif: menyimpan nilai PTS/STS, PAS, SAS.
3. nilai_rapor: menyimpan rekap akhir hasil sinkronisasi rapor.
4. master_lm: referensi Learning Module (LM), dipakai untuk daftar pertemuan dan auto-deskripsi.
5. setting_bobot_nilai: bobot kalkulasi nilai rapor.
6. setting_aturan_nilai: aturan predikat nilai rapor.

Data siswa aktif selalu ditarik dari anggota_rombel berdasarkan rombel + tahun ajaran + semester.

## Daftar Route Tiap Menu

Definisi route ada di backend/app/Config/Routes.php pada grup guru mapel.

### Nilai Kolektif

1. GET /guru/nilai-kolektif
2. GET /guru/nilai-kolektif/download
3. POST /guru/nilai-kolektif/import

### Nilai Formatif

1. GET /guru/nilai-formatif/
2. GET /guru/nilai-formatif/get-students
3. GET /guru/nilai-formatif/get-grades
4. POST /guru/nilai-formatif/save-nilai
5. GET /guru/nilai-formatif/template
6. POST /guru/nilai-formatif/import
7. GET /guru/nilai-formatif/template-all
8. POST /guru/nilai-formatif/import-all
9. GET /guru/nilai-formatif/get-assignments
10. GET /guru/nilai-formatif/get-jumlah-lm

### Nilai Sumatif

1. GET /guru/nilai-sumatif
2. GET /guru/nilai-sumatif/get-siswa (alias getNilaiSiswa)
3. POST /guru/nilai-sumatif/save-draft
4. POST /guru/nilai-sumatif/update-status

### Nilai Rapor

1. GET /guru/nilai-rapor/
2. GET /guru/nilai-rapor/get-penugasan/{ta_id}
3. POST /guru/nilai-rapor/get-data
4. POST /guru/nilai-rapor/sync

## 1) Cara Kerja Menu Nilai Kolektif

Menu ini adalah jalur import massal (Excel) untuk mengisi nilai formatif dan sumatif sekaligus.

### Tujuan

1. Guru download template sesuai TA, kelas, mapel, dan jenis semester.
2. Guru isi nilai di Excel.
3. Guru upload ulang untuk diproses ke database.

### Alur Penggunaan (dari sisi guru)

1. Buka menu Nilai Kolektif.
2. Pilih Tahun Ajaran, Kelas, Mapel, dan Jenis Format (tengah/akhir semester).
3. Klik tombol download template.
4. Isi nilai di file Excel.
5. Upload file .xlsx pada panel import.
6. Sistem menampilkan notifikasi sukses/gagal.

### Alur Teknis

1. View: backend/app/Views/GuruMapel/nilai-kolektif.php
2. JS: backend/public/assets/js/GuruMapel/nilai-kolektif.js
3. Controller: backend/app/Controllers/GuruMapel/NilaiKolektifController.php

Fungsi downloadTemplate:

1. Membaca filter ta, kelas, mapel, jenis dari query string.
2. Menentukan semester dari tahun_ajaran yang dipilih.
3. Mengambil siswa aktif dari anggota_rombel (bukan dari file lama).
4. Menghitung jumlah LM dinamis dari master_lm sesuai mapel, tingkat, semester, kategori.
5. Membuat template Excel berisi kolom NH, UH, dan sumatif (PTS/PAS/SAS).

Fungsi importExcel:

1. Validasi file upload harus valid dan berekstensi xlsx.
2. Membaca metadata template dari sel C4 (kelas), C5 (mapel), C6 (jenis semester).
3. Membaca kode kolom tersembunyi di baris 7, misalnya NH_1, UH_3, pts, pas, sas.
4. Loop siswa dari baris 10 ke bawah berdasarkan NIS.
5. Menyimpan ke:
   - nilai_formatif untuk NH/UH
   - nilai_sumatif untuk PTS/PAS/SAS
6. Proses memakai transaksi database agar konsisten.

### Mapping Nilai Excel

1. NH_x -> nilai_formatif jenis_penilaian = Tugas
2. UH_x -> nilai_formatif jenis_penilaian = Ulangan
3. pts/pas/sas -> nilai_sumatif jenis_sumatif = pts/pas/sas

### Catatan Penting

1. File Excel tidak disimpan permanen sebagai arsip; file hanya dibaca dari temporary upload.
2. Kategori semester diturunkan dari jenis template (Tengah Semester atau Akhir Semester).
3. Jika teks kelas/mapel di template tidak cocok dengan master data, import bisa gagal atau tidak masuk target yang diharapkan.

## 2) Cara Kerja Menu Nilai Formatif

Menu ini dipakai untuk input nilai per pertemuan (manual di tabel) dengan autosave, lock, dan import Excel per pertemuan atau global.

### Tujuan

1. Input nilai harian (Tugas) dan ulangan (Ulangan) per pertemuan.
2. Menyimpan draft otomatis.
3. Mengunci data agar final.
4. Mendukung import Excel single meeting dan all meeting.

### Alur Penggunaan (dari sisi guru)

1. Pilih Kategori (Tengah/Akhir Semester).
2. Pilih Tahun Ajaran.
3. Pilih pasangan Kelas + Mapel.
4. Pilih Jenis Penilaian (Tugas/Ulangan).
5. Pilih Pertemuan.
6. Isi nilai per siswa (predikat dan keterangan bisa terisi otomatis dari LM).
7. Biarkan autosave berjalan, atau klik Simpan Draft.
8. Klik Simpan dan Kunci jika final.
9. Opsional: gunakan import Excel dari modal import.

### Alur Teknis

1. View: backend/app/Views/GuruMapel/nilai-formatif.php
2. JS: backend/public/assets/js/GuruMapel/nilai-formatif.js
3. Controller: backend/app/Controllers/GuruMapel/NilaiFormatifController.php

Endpoint teknis utama:

1. get-assignments: mengambil daftar kelas-mapel guru per tahun ajaran.
2. get-jumlah-lm: mengambil pertemuan dinamis dari master_lm.
3. get-students: mengambil siswa aktif di anggota_rombel.
4. get-grades: mengambil nilai formatif existing sesuai filter.
5. save-nilai: simpan draft/terkunci (upsert per siswa).

### Mekanisme Penyimpanan

1. Nilai disimpan pada tabel nilai_formatif.
2. Kunci unik logis disesuaikan kombinasi: siswa, mapel, jenis, pertemuan, tahun_ajaran, semester, kategori.
3. Jika data ada -> update, jika belum -> insert.
4. Status simpan menggunakan status_simpan: draft atau terkunci.

### Predikat dan Catatan Otomatis

Predikat default:

1. A: >= 90
2. B: >= 80
3. C: >= KKM
4. D: < KKM

Catatan diisi otomatis dari master_lm:

1. Prioritas pertama: deskripsi_a/deskripsi_b/deskripsi_c/deskripsi_d.
2. Fallback: kalimat template berbasis deskripsi_lm.

### Import Excel Formatif

Ada dua mode:

1. Single pertemuan: template dan import untuk 1 pertemuan.
2. Global: template dan import banyak pertemuan sekaligus.

Keduanya tetap berujung ke upsert nilai_formatif dengan filter tahun ajaran/semester/kategori.

## 3) Cara Kerja Menu Nilai Sumatif

Menu ini dipakai untuk input nilai ujian sumatif (PTS/STS/PAS/SAS), dengan workflow status Draft -> Siap Validasi -> Terkunci.

### Tujuan

1. Input nilai sumatif per siswa.
2. Simpan draft (termasuk autosave per perubahan).
3. Tandai siap validasi.
4. Kunci final agar tidak bisa diedit.

### Alur Penggunaan (dari sisi guru)

1. Pilih Tahun Ajaran.
2. Pilih Jenis Sumatif (pts/pas/sas).
3. Klik Load Data.
4. Isi nilai dan deskripsi.
5. Simpan draft.
6. Klik Siap Validasi jika selesai.
7. Klik Lock jika final.

### Alur Teknis

1. View: backend/app/Views/GuruMapel/nilai-sumatif.php
2. JS: backend/public/assets/js/GuruMapel/nilai-sumatif.js
3. Controller: backend/app/Controllers/GuruMapel/NilaiSumatifController.php

Endpoint teknis utama:

1. getNilaiSiswa: mengambil daftar siswa + nilai existing (left join nilai_sumatif).
2. save-draft: menyimpan nilai batch ke nilai_sumatif dengan status draft.
3. update-status: mengubah status ke siap_validasi, draft, atau terkunci.

### Mekanisme Penyimpanan

1. Tabel utama: nilai_sumatif.
2. Upsert memakai kombinasi: siswa_id + mapel_id + tahun_ajaran_id + jenis_sumatif.
3. Field status dipakai untuk kontrol workflow.

### Arti Status

1. draft: nilai masih bisa diubah.
2. siap_validasi: menunggu finalisasi, tombol dan alur berubah.
3. terkunci: nilai final, input readonly, tidak boleh diubah dari halaman guru.

### Bobot dan Predikat

Menu sumatif menampilkan bobot dari setting_bobot_nilai sesuai sub_kategori yang dipilih, agar guru tahu kontribusi jenis ujian terhadap rapor.

## 4) Cara Kerja Menu Nilai Rapor

Menu ini adalah tahap rekap akhir. Data tidak diketik manual per siswa, tetapi dikalkulasi dari nilai formatif + sumatif sesuai bobot dan aturan predikat.

### Tujuan

1. Menarik data nilai mentah per siswa.
2. Menampilkan preview rata-rata NH/UH/sumatif.
3. Menyinkronkan hasil akhir ke nilai_rapor.

### Alur Penggunaan (dari sisi guru)

1. Pilih kategori rapor (Tengah/Akhir Semester).
2. Pilih tahun ajaran.
3. Pilih kelas.
4. Pilih mapel.
5. Klik Sinkronisasi Kelas Ini.
6. Sistem menghitung lalu menyimpan nilai akhir rapor.

### Alur Teknis

1. View: backend/app/Views/GuruMapel/nilai-rapor.php
2. JS: backend/public/assets/js/GuruMapel/nilai-rapor.js
3. Controller: backend/app/Controllers/GuruMapel/NilaiRaporController.php

Endpoint teknis utama:

1. get-penugasan/{ta_id}: daftar kelas-mapel guru (gabungan guru_mapel dan jadwal_pelajaran).
2. get-data: preview perhitungan rapor sebelum sync.
3. sync: kalkulasi dan upsert ke nilai_rapor.

### Rumus dan Bobot

Bobot dibaca dari setting_bobot_nilai. Jika tidak ada, sistem memakai default:

1. Tengah Semester: NH 35%, UH 35%, STS 30%
2. Akhir Semester: NH 30%, UH 30%, STS/PAS 15%, SAS 25%

Predikat dibaca dari setting_aturan_nilai. Jika kosong, fallback:

1. > = 90: Sangat Baik
2. > = 80: Baik
3. > = 70: Cukup
4. < 70: Perlu Bimbingan

### Output Sinkronisasi

Hasil akhir disimpan ke nilai_rapor dengan data utama:

1. rata_formatif
2. rata_sumatif
3. nilai_akhir
4. predikat
5. deskripsi_tertinggi (jika kolom tersedia)
6. deskripsi_terendah (jika kolom tersedia)

## Hubungan Antar Menu

Urutan ideal operasional:

1. Isi nilai melalui Nilai Formatif dan Nilai Sumatif, atau sekaligus lewat Nilai Kolektif.
2. Pastikan data sudah benar dan status final sesuai kebutuhan.
3. Buka Nilai Rapor untuk melihat preview.
4. Klik sinkronisasi untuk menyimpan rekap final ke nilai_rapor.

Nilai Rapor tidak berdiri sendiri. Menu ini bergantung pada data di nilai_formatif dan nilai_sumatif.

## Checklist Jika Data Tidak Muncul

Jika guru merasa data hilang, cek berurutan:

1. Tahun ajaran yang dipilih sudah sama saat input/import.
2. Kategori semester (Tengah/Akhir) sesuai.
3. Kelas dan mapel sesuai penugasan guru.
4. Jenis penilaian/pertemuan (untuk formatif) sesuai.
5. Jenis sumatif (pts/pas/sas) sesuai.
6. Siswa masih aktif dan terdaftar di anggota_rombel TA/semester itu.

## Catatan Implementasi Saat Ini

1. Import Excel kolektif/formative memproses isi file menjadi data operasional, bukan menyimpan arsip file.
2. Workflow status paling ketat ada di menu sumatif (draft -> siap_validasi -> terkunci).
3. Nilai rapor bersifat hasil kalkulasi sinkronisasi, bukan input manual satu per satu.

## File Referensi Utama

1. backend/app/Config/Routes.php
2. backend/app/Controllers/GuruMapel/NilaiKolektifController.php
3. backend/app/Controllers/GuruMapel/NilaiFormatifController.php
4. backend/app/Controllers/GuruMapel/NilaiSumatifController.php
5. backend/app/Controllers/GuruMapel/NilaiRaporController.php
6. backend/app/Views/GuruMapel/nilai-kolektif.php
7. backend/app/Views/GuruMapel/nilai-formatif.php
8. backend/app/Views/GuruMapel/nilai-sumatif.php
9. backend/app/Views/GuruMapel/nilai-rapor.php
10. backend/public/assets/js/GuruMapel/nilai-kolektif.js
11. backend/public/assets/js/GuruMapel/nilai-formatif.js
12. backend/public/assets/js/GuruMapel/nilai-sumatif.js
13. backend/public/assets/js/GuruMapel/nilai-rapor.js
