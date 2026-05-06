# Analisa Prosedur Pendaftaran Siswa ke Kegiatan Ekstrakurikuler

Dokumen ini menjelaskan alur teknis dan operasional dalam mendaftarkan siswa ke kegiatan ekstrakurikuler di aplikasi eRapotEasy.

## 1. Persiapan Master Data Ekstrakurikuler
Sebelum siswa dapat didaftarkan, Admin harus memastikan daftar kegiatan ekstrakurikuler sudah tersedia di sistem.
- **Menu**: Admin > Master Data > Master Ekskul
- **Controller**: `App\Controllers\Admin\MasterEkskulController`
- **Tabel Database**: `master_ekskul` (kolom: `nama_ekskul`, `status`)
- **Prosedur**: Admin menambah atau mengaktifkan daftar kegiatan yang tersedia untuk tahun ajaran berjalan.

## 2. Alur Pendaftaran Siswa
Pendaftaran siswa ke kegiatan ekstrakurikuler dilakukan melalui profil siswa. Sistem ini menggunakan pendekatan "Fixed Slots" di mana setiap siswa dapat mengikuti maksimal 3 kegiatan ekstrakurikuler dalam satu periode.

### A. Melalui Interface Admin (Input Manual/Edit)
- **Menu**: Admin > Data Siswa > [Edit Siswa]
- **Controller**: `App\Controllers\Admin\SiswaController` (Method: `store` & `update`)
- **Tabel Database**: `siswa`
- **Kolom Terkait**: 
  - `ekskul_1` (INT): ID dari `master_ekskul`
  - `ekskul_2` (INT): ID dari `master_ekskul`
  - `ekskul_3` (INT): ID dari `master_ekskul`
- **Validasi**: Sistem akan menolak jika siswa memilih kegiatan yang sama lebih dari satu kali (Validasi Ekskul Kembar).

### B. Sinkronisasi Data (Auto-Detect)
Sistem pendaftaran ini bersifat statis di profil siswa namun dinamis di penilaian. 
- Saat Wali Kelas membuka menu penilaian, sistem akan melakukan **Auto-Detect** terhadap isi kolom `ekskul_1`, `ekskul_2`, dan `ekskul_3` pada tabel `siswa` untuk siswa di kelas tersebut.
- Jika kolom tersebut kosong, siswa dianggap belum mendaftar ke kegiatan apapun.

## 3. Verifikasi dan Penilaian oleh Wali Kelas
Setelah didaftarkan oleh Admin, Wali Kelas bertanggung jawab untuk memverifikasi dan memberikan nilai.
- **Menu**: Wali Kelas > Penilaian > Nilai Ekstrakurikuler
- **Controller**: `App\Controllers\WaliKelas\NilaiEkskulController`
- **Logic**: 
  - Query mengambil daftar siswa dari `anggota_rombel` (Mesin Waktu) dan melakukan JOIN ke tabel `siswa` untuk mendapatkan data `ekskul_1, 2, 3`.
  - Jika siswa terdeteksi memiliki data ekskul di profilnya, tombol **"Beri Nilai"** akan aktif.
  - Jika belum ada data di profil, muncul peringatan: *"Siswa belum memilih Ekskul. Hubungi Admin Sekolah untuk mendaftarkan ekskul siswa ini."*

## 4. Struktur Database Terkait
| Tabel | Kolom Utama | Deskripsi |
|-------|-------------|-----------|
| `master_ekskul` | `id`, `nama_ekskul` | Daftar seluruh kegiatan ekskul. |
| `siswa` | `ekskul_1`, `ekskul_2`, `ekskul_3` | Referensi kegiatan yang diikuti siswa. |
| `nilai_ekskul` | `siswa_id`, `ekskul_id`, `predikat`, `deskripsi` | Data nilai akhir yang akan tampil di rapor. |

## 5. Kesimpulan Prosedur
Prosedur pendaftaran dilakukan secara terpusat oleh **Admin** (atau user dengan hak akses edit siswa) dengan cara mengisi pilihan ekskul pada **Profil Siswa**. Wali Kelas tidak mendaftarkan siswa secara langsung di menu penilaian, melainkan hanya menginput nilai berdasarkan daftar ekskul yang sudah diatur di profil siswa tersebut.

---
*Dibuat oleh: Antigravity AI*  
*Tanggal: 6 Mei 2026*
