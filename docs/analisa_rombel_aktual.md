# Analisa Rombel Aktual vs Data Master Siswa

## Masalah
Terdapat perbedaan antara data kelas yang muncul pada tabel utama (Daftar Siswa) dengan data yang muncul saat tombol **Edit** diklik pada kolom "Rombel Saat Ini".

## Temuan Analisa

### 1. Definisi "Rombel Aktual"
**Rombel Aktual** adalah kelas siswa yang terdaftar pada **Tahun Ajaran Aktif**. Sistem menggunakan arsitektur "Mesin Waktu" untuk melacak riwayat kelas siswa setiap semester/tahun melalui tabel `anggota_rombel`.

*   **Tabel Daftar Siswa (Main Table):** Menggunakan logika `COALESCE(ar.rombel_id, s.rombel_id)`. Artinya, sistem memprioritaskan data dari tabel `anggota_rombel` (riwayat aktif). Jika data riwayat tidak ditemukan, baru mengambil data dari tabel `siswa` (master).
*   **Tombol Edit (Form):** Mengambil data langsung dari kolom `rombel_id` di tabel `siswa`.

### 2. Penyebab Perbedaan
Inkonsistensi terjadi karena proses **Kenaikan Kelas** atau **Pindahan Rombel** hanya mengisi/memperbarui tabel `anggota_rombel` (untuk keperluan raport dan absensi semester aktif), namun tidak selalu memperbarui kolom `rombel_id` pada tabel master `siswa` yang merupakan data "asal" siswa.

## Panduan Penempatan & Pemindahan Rombel

### Lokasi Menu
Proses penempatan dan pemindahan siswa dapat diakses melalui:
**Menu Master Akademik** > **Tingkat & Rombel** (`admin/tingkat-rombel`)

### FAQ Penempatan Rombel

#### Siapa yang menentukan penempatan siswa?
Penempatan siswa ditentukan sepenuhnya oleh **Admin Sekolah** melalui dashboard admin.

#### Bagaimana prosedur memindahkan siswa ke rombel lain?
Jika siswa ingin dipindah ke rombel lain dalam tingkat/tahun ajaran yang sama:
1.  Buka menu **Tingkat & Rombel**.
2.  Klik pada rombel asal untuk melihat daftar siswa.
3.  Pilih siswa yang akan dipindah.
4.  Gunakan fitur **"Transfer Siswa"** atau **"Pindahkan ke Kelas Lain"**.
5.  Pilih kelas tujuan dan konfirmasi.

#### Bagaimana sistem mencatat perpindahan tersebut?
Saat proses transfer dilakukan melalui menu Tingkat & Rombel, sistem melakukan dua hal secara otomatis:
1.  **Update Tabel `siswa`:** Memperbarui kolom `rombel_id` agar data master sinkron.
2.  **Update Tabel `anggota_rombel`:** Memperbarui record riwayat untuk Tahun Ajaran aktif agar raport dan monitoring nilai mengikuti kelas yang baru.

> [!IMPORTANT]
> Sangat disarankan untuk melakukan pemindahan siswa melalui menu **Tingkat & Rombel** daripada mengubahnya melalui tombol **Edit** di Daftar Siswa, karena menu Tingkat & Rombel menjamin sinkronisasi ke tabel riwayat (Mesin Waktu).
