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

## Dampak Data & Integrasi Sistem
> [!IMPORTANT]
> Dengan perubahan ini, sumber data utama (Source of Truth) untuk absensi bergeser sepenuhnya ke tabel `rekap_absensi`.

### Perubahan Pengambilan Data (Data Retrieval)
1. **Prioritas Utama**: Semua modul pencetakan rapor dan dashboard harus memprioritaskan pengambilan data dari tabel `rekap_absensi` berdasarkan `tahun_ajaran_id` dan `semester`.
2. **Penghentian Fallback**: Penggunaan tabel `absensi_harian` sebagai sumber data harus dihentikan secara bertahap atau hanya dijadikan referensi sejarah (History). Fitur cetak rapor tidak boleh lagi mengambil data dari `absensi_harian` jika data di `rekap_absensi` sudah tersedia.

### Modul yang Terintegrasi (Cross-Module Sync)
Fitur-fitur berikut juga harus disesuaikan untuk mengikuti skema data baru ini:
- **Admin -> Cetak Rapor**: Mengubah logika pengambilan data agar memprioritaskan `rekap_absensi` sebelum mengecek `absensi_harian`.
- **Wali Kelas -> Preview Rapor**: (Sudah disesuaikan) Mengambil data rekap berdasarkan semester yang aktif.
- **Wali Murid -> Dashboard & Akademik**: Menambahkan filter semester pada query `rekap_absensi` agar data yang ditampilkan presisi.

## Langkah Implementasi (Lanjutan)
1.  **Analisis & Persiapan**: Memastikan struktur tabel `rekap_absensi` sudah sesuai.
2.  **Modifikasi Backend**: Mengubah API endpoint untuk mendukung pengambilan dan penyimpanan data semester.
3.  **Pembaruan UI**: Mengubah tampilan dashboard absensi agar lebih fokus pada total semester.
4.  **Sinkronisasi Modul Rapor**: Memperbarui `CetakRaporController` (Admin) dan `AkademikController` (Orang Tua) agar konsisten menggunakan data rekap.
5.  **Verifikasi Akhir**: Memastikan data yang diinput tampil dengan benar di Preview Rapor (Wali Kelas), Cetak Rapor (Admin), dan Portal Orang Tua.

