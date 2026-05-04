# Analisa Ketidaksesuaian Daftar Siswa (Admin vs Template Excel)

Laporan ini mendokumentasikan hasil investigasi mendalam terhadap proses pengambilan data siswa pada modul Dashboard Admin dibandingkan dengan modul Penilaian (Guru Mapel) dan Absensi (Wali Kelas).

## 1. Ringkasan Temuan
Ditemukan perbedaan logika pengambilan data (*query*) yang signifikan antara modul Admin dan modul transaksi (Nilai/Absen), yang berpotensi menyebabkan daftar nama siswa di template Excel tidak sinkron dengan apa yang dilihat Admin.

## 2. Detail Analisa Teknis

### A. Inkonsistensi Filter Semester (Akar Masalah Utama)
Modul Penilaian dan Absensi menggunakan filter ketat berdasarkan Semester, sementara Dashboard Admin cenderung hanya memfilter berdasarkan Tahun Ajaran.

*   **File Acuan (Penilaian/Absensi):** 
    *   `NilaiFormatifController.php` (Method: `downloadTemplate`)
    *   `AbsensiKelasController.php` (Method: `getAbsensiData`)
    *   **Logika:** `WHERE ar.tahun_ajaran_id = $ta_id AND ar.semester = $semester`
*   **File Acuan (Admin):** 
    *   `SiswaController.php` (Method: `getAll`)
    *   **Logika:** `JOIN anggota_rombel ar ON ... AND ar.tahun_ajaran_id = $ta_id` (Tanpa filter Semester).

**Dampak:** Jika seorang siswa dipindahkan kelasnya di tengah tahun (misal: dari Semester Ganjil ke Genap), Dashboard Admin akan menampilkan **dua baris data** untuk satu siswa (karena ada dua record di `anggota_rombel`), sedangkan template Excel hanya akan menampilkan satu baris sesuai semester aktif.

### B. Fallback Rombel ID (`COALESCE`)
Dashboard Admin memiliki mekanisme "aman" untuk menampilkan siswa meskipun data riwayatnya belum sinkron, namun modul template tidak.

*   **Modul Admin:** Menggunakan `JOIN rombel r ON r.id = COALESCE(ar.rombel_id, s.rombel_id)`. Jika siswa belum masuk tabel riwayat `anggota_rombel`, sistem akan mengambil data dari tabel utama `siswa`.
*   **Modul Template:** Hanya melakukan JOIN ke `anggota_rombel`. 
*   **Dampak:** Jika admin menambahkan siswa ke kelas via profil siswa (hanya update `siswa.rombel_id`) tanpa melakukan sinkronisasi rombel, maka siswa tersebut **terlihat di Admin** tapi **hilang di template Excel**.

### C. Filter Status Siswa
*   **Modul Template:** Secara eksplisit hanya mengambil siswa dengan `status_siswa = 'Aktif'`.
*   **Dashboard Admin:** Mengambil semua siswa dalam database (termasuk Lulus/Keluar jika record rombelnya masih tersisa di DB).
*   **Dampak:** Siswa yang sudah tidak aktif namun datanya belum dibersihkan dari rombel akan tetap muncul di Admin, namun tidak akan tercetak di template Excel.

### D. Logika Pengurutan (Sorting)
*   **Modul Template:** Menggunakan `ORDER BY s.nama_lengkap ASC`.
*   **Dashboard Admin:** Backend tidak memberikan instruksi pengurutan eksplisit (bergantung pada default database atau pengurutan di sisi frontend React/Vue).
*   **Dampak:** Urutan nama di Excel bisa berbeda dengan Admin, yang mempersulit verifikasi manual oleh pengguna.

## 3. Contoh Kasus (Skenario Error)
1.  **Skenario Pindah Semester:** Siswa A di Ganjil di Kelas 7.1, di Genap di Kelas 7.2. Admin (tanpa filter semester) akan melihat Siswa A di daftar 7.1 dan 7.2 sekaligus jika hanya melihat Tahun Ajaran. Template Excel 7.1 Genap tidak akan menampilkan Siswa A.
2.  **Skenario Input Manual:** Admin mengubah kelas siswa via "Edit Profil" saja. Kolom `siswa.rombel_id` berubah, tapi tabel `anggota_rombel` belum diupdate. Di Admin (karena ada `COALESCE`), siswa muncul di kelas baru. Di Template Excel (hanya baca `anggota_rombel`), siswa tetap di kelas lama atau hilang.

## 4. Rekomendasi Perbaikan (Acuan Mendatang)

| Lokasi Perbaikan | Tindakan |
| :--- | :--- |
| **Backend (SiswaController::getAll)** | Tambahkan filter `semester` pada JOIN `anggota_rombel` agar konsisten dengan modul transaksi. |
| **Backend (Semua Template)** | Gunakan standarisasi `ORDER BY s.nama_lengkap ASC` di semua query daftar siswa. |
| **Sistem Sinkronisasi** | Tambahkan validasi otomatis: jika `siswa.rombel_id` berubah, sistem harus otomatis mengupdate/insert ke `anggota_rombel` sesuai TA/Semester aktif. |
| **UI Dashboard Admin** | Tambahkan indikator "Semester" pada filter global agar admin sadar data yang dilihat adalah data spesifik semester tertentu. |

---
*Dokumen ini dibuat sebagai acuan teknis untuk proses refactoring di masa mendatang.*
