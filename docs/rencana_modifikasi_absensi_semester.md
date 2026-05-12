# Rencana Modifikasi Proses Absensi Semester

Dokumen ini menjelaskan rencana untuk menyederhanakan proses absensi siswa oleh Wali Kelas dari yang sebelumnya berbasis harian menjadi berbasis total per semester.

## Tujuan Modifikasi
- Membuat proses absensi lebih ringkas dan efisien bagi Wali Kelas.
- Wali Kelas hanya perlu memasukkan total Sakit (S), Izin (I), dan Alpa (A) untuk satu semester.
- Menghilangkan kebutuhan untuk input absensi per tanggal.
- Menambahkan filter Tahun Ajaran dan Semester agar data lebih terorganisir.

## Komponen yang Akan Diubah

### 1. Database
- **Tabel Utama**: `rekap_absensi`
- **Fungsi**: Tabel ini akan menjadi sumber data utama untuk absensi yang ditampilkan di rapor. 
- **Field yang digunakan**: `siswa_id`, `id_tahun_ajaran`, `semester`, `sakit`, `izin`, `alpha`.

### 2. Backend (Controller & Model)
- **`AbsensiKelasController`**:
    - Memperbarui fungsi `index` untuk menyediakan data Tahun Ajaran dan Semester untuk filter.
    - Memperbarui `getAbsensiData` agar mengambil data langsung dari `rekap_absensi` berdasarkan filter yang dipilih.
    - Memperbarui `saveAbsensi` untuk menyimpan total S/I/A langsung ke `rekap_absensi` (menggunakan logika UPSERT).
    - Menyesuaikan `importAbsensi` untuk mendukung format CSV baru (Total per Semester).
    - Menambahkan fitur `downloadTemplate` untuk format baru.

### 3. Frontend (View & JS)
- **View (`absensi-kelas.php`)**:
    - Menambahkan dropdown filter untuk **Tahun Ajaran** dan **Semester**.
    - Menyederhanakan tabel absensi dengan menghapus kolom tanggal dan menggantinya dengan kolom input/display total S, I, A.
- **JavaScript (`absensi-kelas.js`)**:
    - Menghapus logika kalender harian.
    - Menambahkan state untuk filter yang aktif.
    - Memperbarui modal "Tambah Data" agar berisi field input untuk total Sakit, Izin, dan Alpa.

### 4. Fitur Import/Export
- **Import CSV**: Format baru akan mengikuti struktur: `NISN`, `Nama Siswa`, `Sakit`, `Izin`, `Alpha`.
- **Export CSV**: Akan menghasilkan data rekap total per semester yang sedang aktif difilter.

## Langkah Implementasi
1.  **Analisis & Persiapan**: Memastikan struktur tabel `rekap_absensi` sudah sesuai.
2.  **Modifikasi Backend**: Mengubah API endpoint untuk mendukung pengambilan dan penyimpanan data semester.
3.  **Pembaruan UI**: Mengubah tampilan dashboard absensi agar lebih fokus pada total semester.
4.  **Integrasi Fitur**: Mengupdate logika import/export dan template CSV.
5.  **Verifikasi**: Memastikan data yang diinput tampil dengan benar di Preview Rapor.

## Dampak Data
> [!WARNING]
> Data absensi harian yang sudah ada di tabel `absensi_harian` tidak akan lagi digunakan dalam tampilan utama Papan Absensi yang baru. Wali kelas disarankan untuk melakukan rekap manual jika ingin memindahkan data dari harian ke total semester sebelum fitur ini diaktifkan sepenuhnya.
