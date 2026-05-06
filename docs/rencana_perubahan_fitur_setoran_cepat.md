# Rencana Perubahan Fitur Setoran Cepat Tahfidz

Rencana ini merinci langkah-langkah teknis untuk memodifikasi fitur "Setoran Cepat" sesuai dengan kebutuhan:
1. Guru dapat memilih beberapa target surah/blok sekaligus dalam 1 juz yang sama.
2. Struktur database tetap menggunakan pola 1 surah = 1 nilai (1 baris data di `setoran_tahfidz`).
3. Form input nilai disederhanakan hanya menjadi "Hafalan", menghilangkan input Huruf, Mad, dan Tajwid.

## Penemuan Penting (Bug Existing)
Saat menganalisa backend (`SetoranController.php`), ditemukan bahwa validasi untuk mengecek ketersediaan data setoran (untuk melakukan `update` atau `insert`) hanya mengecek `siswa_id`, `tanggal`, dan `juz_id`.
**Artinya:** Jika saat ini guru memasukkan setoran surah pertama, lalu memasukkan setoran surah kedua di juz yang sama pada hari yang sama, data surah pertama akan tertimpa (overwrite) oleh surah kedua.
Oleh karena itu, penyesuaian query di Backend wajib dilakukan agar struktur "1 surah 1 nilai" dapat berjalan berdampingan tanpa saling menimpa.

---

## 1. Modifikasi Tampilan (View)
**File:** `backend/app/Views/tahfidz/dashboard.php`

- **Ubah Elemen Select Surah/Blok:**
  Ubah `<select id="inputSurahCepat">` dengan menambahkan atribut `multiple="multiple"`. Karena fitur ini menggunakan TailwindCSS, kita dapat memanfaatkan UI multi-select bawaan HTML atau styling custom sederhana untuk mendukung pemilihannya (misal dengan menahan tombol `Ctrl`).
- **Hapus Input 4 Pilar yang Tidak Diperlukan:**
  Hapus elemen HTML untuk `<input id="inputValHRF">` (Huruf), `<input id="inputValM">` (Mad), dan `<input id="inputValT">` (Tajwid).
- **Penyesuaian Layout Form Nilai:**
  Ubah container form nilai dari yang awalnya dibagi menjadi 4 kolom (`grid-cols-4`) menjadi 1 kolom yang difokuskan pada `inputValHFL` (Hafalan).

---

## 2. Penyesuaian Logika Frontend (Javascript)
**File:** `backend/public/assets/js/Tahfidz/dashboard.js`

- **Fungsi `calcAvgCepat()`:**
  Hapus logika perhitungan rata-rata (average). Fungsi ini sekarang hanya cukup mengambil nilai dari `inputValHFL` dan langsung mengoperkannya ke fungsi `hitungTaqdir(nilai)`.
- **Fungsi `submitSetoranCepat()`:**
  - Tangkap semua *value* yang dipilih dari `<select id="inputSurahCepat" multiple>`. Nilai ini sekarang berbentuk *Array*.
  - Lakukan iterasi (looping) untuk setiap surah yang dipilih.
  - Di dalam loop, gunakan `fd.append()` untuk memasukkan data `siswa_id`, `juz_id`, `surah_id`, dan nilai-nilainya. Ini akan menghasilkan array di dalam `FormData` yang panjangnya sama dengan jumlah surah yang dipilih.
  - Untuk `nilai_hrf`, `nilai_m`, dan `nilai_t`, tetap kirimkan dengan nilai `0` (hardcode) agar proses backend tetap dapat berjalan tanpa error (jika db column mensyaratkan not null).

---

## 3. Penyesuaian Logika Backend (Controller)
**File:** `backend/app/Controllers/Tahfidz/SetoranController.php`

- **Fungsi `save()`:**
  Logika loop (`for ($i = 0; $i < count($siswa_id); $i++)`) yang ada saat ini sudah mendukung untuk memproses multiple data dari frontend secara langsung.
  Namun, query pengecekan data existing harus diperbaiki.
  **Sebelumnya:**
  ```php
  $existing = $this->db->table('setoran_tahfidz')
      ->where('siswa_id', $s_id)
      ->where('tanggal', $tanggal)
      ->where('juz_id', $j_id)
      ->get()->getRowArray();
  ```
  **Harus diubah menjadi:**
  ```php
  $existing = $this->db->table('setoran_tahfidz')
      ->where('siswa_id', $s_id)
      ->where('tanggal', $tanggal)
      ->where('juz_id', $j_id)
      ->where('surah_id', $s_sur)   // <--- Penambahan Wajib
      ->where('ayat', $ayat_fix)    // <--- Penambahan Wajib
      ->get()->getRowArray();
  ```
- **Fungsi `importCsv()` (Opsional tetapi sangat disarankan):**
  Bug yang sama juga terdapat pada fungsi impor CSV. Jika sekolah melakukan impor melalui Excel untuk beberapa surah di juz yang sama pada hari yang sama, datanya akan saling menimpa. Query `$existing` di fungsi ini juga perlu ditambahkan `where('surah_id', ...)` dan `where('ayat', ...)`.

## Kesimpulan
Plan ini dirancang untuk tidak merusak skema database yang sudah berjalan (1 surah 1 nilai) dan meminimalisir interupsi pada modul Tahfidz yang lain. Mengingat logika kirim data dan loop array sudah didukung oleh backend (karena dikirim sebagai array `siswa_id[]`, `juz_id[]`, `surah_id[]` dari frontend dan dilooping di backend), perubahan terberat (dan terpenting) hanyalah pada pengkondisian query `$existing`.
