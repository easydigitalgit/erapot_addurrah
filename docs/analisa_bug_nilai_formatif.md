# Analisa Bug & Prosedur: Nilai Formatif (Halaman /guru/nilai-formatif)

Ditemukan beberapa kendala yang menyebabkan nilai tidak tampil setelah proses upload kolektif dilakukan, baik dari sisi prosedur penggunaan maupun bug pada kode program.

## 1. Analisa Masalah: Nilai Tidak Tampil di Halaman
Berdasarkan screenshot dan analisa kode, alasan utama nilai tidak muncul adalah:

### A. Prosedur: Dropdown "Pertemuan Ke-" Belum Dipilih
Di halaman Nilai Formatif, data nilai bersifat **per pertemuan**. Sistem tidak akan menampilkan tabel nilai jika **"Pertemuan Ke-"** belum dipilih. 
- **Status di Screenshot**: Menampilkan `-- Pertemuan --`.
- **Dampak**: Frontend JS (`nilai-formatif.js`) secara otomatis mengosongkan tabel jika parameter `pertemuan` kosong.

### B. Prasyarat: Master LM Belum Diisi
Dropdown "Pertemuan Ke-" mengambil referensi dari tabel `master_lm`. 
- Jika Guru belum mengisi **Master Lingkup Materi (LM)** untuk Mata Pelajaran dan Kelas tersebut, maka dropdown Pertemuan akan kosong/tidak ada pilihan.
- Tanpa pilihan pertemuan, user tidak bisa melihat data nilai yang sudah diupload (meskipun data tersebut sudah masuk ke database).

---

## 2. Analisa Kasus Spesifik: MUHAMMAD AFKHAR AYUB (Nilai Kosong)
Berdasarkan investigasi pada file template Excel (`Template_Kolektif_Tengah_Topaz.xlsx`) dan database, ditemukan penyebab yang sangat spesifik:

### A. Duplikasi NIS (Nomor Induk Siswa)
Ditemukan bahwa ada **dua siswa berbeda** yang memiliki NIS yang sama persis di kelas Topaz:
1. **MHD JUSTICIO PRADIPTO** (NIS: `09.25.0078`) - Baris 29 di Excel.
2. **MUHAMMAD AFKHAR AYUB** (NIS: `09.25.0078`) - Baris 31 di Excel.

### B. Cara Kerja Logic Import
Kode pada `NilaiKolektifController.php` (Garis 318) mencari data siswa hanya berdasarkan NIS:
```php
$siswa = $this->db->table('siswa')->where('nis', $nis)->where('rombel_id', $rombel_id)->get()->getRowArray();
```
- Saat sistem memproses baris MUHAMMAD AFKHAR, sistem mencari NIS `09.25.0078`.
- Karena ada dua siswa dengan NIS tersebut, sistem hanya mengambil **hasil pertama** yang ditemukan (dalam hal ini adalah **MHD JUSTICIO PRADIPTO**).
- Akibatnya, nilai milik MUHAMMAD AFKHAR **menimpa** nilai milik JUSTICIO, sementara data MUHAMMAD AFKHAR sendiri tetap kosong di database.

### C. Kesimpulan Kasus
MUHAMMAD AFKHAR tidak mendapatkan nilai karena identitasnya (NIS) "bertabrakan" dengan siswa lain di database, dan sistem import tidak memiliki validasi nama untuk membedakan NIS yang ganda.

---

## 3. Temuan Bug (General/Sistem)
Ditemukan kesalahan logika pada backend yang dapat menyebabkan proses upload seolah-olah berhasil namun data tidak tersimpan, atau template excel yang didownload kosong.

### Bug 1: Kesalahan Query Data Siswa (Import & Download Template)
Di file `NilaiFormatifController.php`, pada fungsi `downloadTemplate`, `importExcel`, dan `importExcelAll`, terdapat logika penarikan data siswa yang salah:

```php
// LOGIKA SALAH (Masih ada di Controller)
$siswas = $db->table('siswa')->where('rombel_id', $rombel_id)->get()->getResultArray();
```

**Masalah**: 
Sistem ini menggunakan mekanisme **"Mesin Waktu"** di mana data siswa per kelas disimpan di tabel `anggota_rombel` berdasarkan Tahun Ajaran dan Semester. Kolom `rombel_id` di tabel `siswa` seringkali kosong atau tidak diperbarui.

**Akibatnya**:
1. **Template Kosong**: Saat klik "Download Template", file Excel yang dihasilkan hanya berisi header tanpa daftar nama siswa.
2. **Import Gagal**: Saat upload file Excel, sistem mencari NIS siswa tersebut di rombel yang salah (menurut tabel `siswa`), sehingga data nilai diabaikan karena dianggap siswa tersebut bukan anggota kelas Topaz.

### Bug 2: Overwriting Variable (Double Logic)
Pada fungsi `downloadTemplate`, kode sebenarnya sudah memiliki logika yang benar di bagian atas, namun kemudian **ditimpa (overwrite)** kembali oleh logika yang salah di bagian bawahnya:

```php
// Garis 450-458: Logika SUDAH BENAR (Menggunakan anggota_rombel)
$builder = $db->table('anggota_rombel ar')...

// Garis 461-463: Logika SALAH (Menimpa variabel $siswas yang sudah benar)
$builder = $db->table('siswa')...
$siswas = $builder->get()->getResultArray(); // <-- Ini penyebab template kosong
```

---

## 3. Rekomendasi Tindakan

### Untuk User (Guru):
1. Pastikan sudah mengisi **Master Lingkup Materi (LM)** untuk Mapel & Kelas yang bersangkutan.
2. Setelah Master LM diisi, pilih **Pertemuan Ke-** pada dropdown di halaman Nilai Formatif.

### Untuk Developer (Fix Bug):
1. **Perbaiki Controller [FIXED]**: Saya telah menghapus baris kode lama yang memanggil `$db->table('siswa')` dan memastikan semua penarikan siswa menggunakan join ke `anggota_rombel`.
2. **Sinkronisasi Import [FIXED]**: Fungsi `importExcelAll` dan `importExcel` (Kolektif) sudah diperbarui untuk merujuk ke tabel `anggota_rombel`.

---
*Analisa dilakukan pada: 2026-04-29*
*Oleh: Antigravity AI*
