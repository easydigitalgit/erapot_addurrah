# Analisa Ketidaksinkronan Data Siswa (Kasus: Ayla Shaufa)

## Deskripsi Masalah
Siswa atas nama **Ayla Shaufa** tidak muncul dalam template daftar nilai untuk kelas **Kyanite**, meskipun di dashboard Admin siswa tersebut sudah tercatat berada di kelas Kyanite.

## Temuan Investigasi

### 1. Dualisme Data Lokasi Siswa
Aplikasi ini menggunakan dua sumber data untuk menentukan kelas seorang siswa:
- **Rombel Aktual (`siswa.rombel_id`)**: Digunakan oleh Dashboard Admin untuk menampilkan daftar siswa saat ini.
- **Mesin Waktu (`anggota_rombel`)**: Digunakan oleh sistem penilaian (Template Excel, Rapor, dll) untuk memastikan data nilai tetap konsisten dengan histori tahun ajaran dan semester tertentu.

### 2. Status Data Ayla Shaufa (ID 89)
Berdasarkan pengecekan database:
- **Tabel `siswa`**: `rombel_id = 19` (Kyanite) &mdash; **Benar**.
- **Tabel `anggota_rombel`**: Terdaftar di `rombel_id = 18` (Intan) untuk Tahun Ajaran 9 (Genap) &mdash; **Salah/Belum Update**.

Karena template nilai menggunakan tabel `anggota_rombel` sebagai filter utama, Ayla tetap dianggap berada di kelas Intan oleh sistem penilaian, sehingga ia tidak "terpanggil" saat sistem menggenerate template untuk kelas Kyanite.

### 3. Penyebab: Celah Sinkronisasi (Sync Gap)
Kami telah melakukan audit terhadap kode sumber dan menemukan bahwa:
- Fitur **Edit Manual** (melalui modal Edit Siswa) sudah memiliki logika sinkronisasi "Mesin Waktu".
- Fitur **Pindah Siswa** (melalui menu Rombel) sudah memiliki logika sinkronisasi "Mesin Waktu".
- **BUG UTAMA**: Fitur **Import Excel (SiswaController::import)** TIDAK memiliki logika sinkronisasi ke tabel `anggota_rombel`. 

Jika user memindahkan siswa dengan cara mengupload file Excel baru yang berisi perubahan nama kelas, maka hanya tabel utama `siswa` yang terupdate, sementara data histori "Mesin Waktu" di `anggota_rombel` tetap tertinggal di kelas lama.

### 4. Controller yang Bertanggung Jawab
Daftar nama siswa pada template Excel dihasilkan oleh:
- **Nilai Kolektif**: `App\Controllers\GuruMapel\NilaiKolektifController::downloadTemplate()` (baris 97-105).
- **Nilai Formatif**: `App\Controllers\GuruMapel\NilaiFormatifController::downloadTemplate()` (baris 445-452).

Kedua fungsi tersebut secara konsisten menggunakan tabel `anggota_rombel` sebagai acuan (filter `rombel_id`), sehingga data yang tidak sinkron di tabel tersebut akan menyebabkan siswa "menghilang" dari template kelas tujuan.

## Rekomendasi Perbaikan

### Langkah Pendek (Fix Data)
Menjalankan script sinkronisasi untuk memperbaiki data Ayla Shaufa dan siswa lainnya yang mungkin mengalami hal serupa agar data `anggota_rombel` sesuai dengan `rombel_id` terbaru.

### Langkah Panjang (Fix Code)
1. Menambahkan logika "Suntikan Mesin Waktu" ke dalam metode `import()` di `SiswaController.php`.
2. Memastikan setiap perubahan `rombel_id` di tabel `siswa` selalu diikuti dengan update/insert di tabel `anggota_rombel` untuk tahun ajaran yang sedang aktif.

---
**Status Analisa**: Selesai
**File Terkait**: 
- `backend/app/Controllers/Admin/SiswaController.php` (Fungsi `import`)
- `backend/app/Controllers/GuruMapel/NilaiFormatifController.php` (Fungsi `downloadTemplate`)
