# Prosedur Reset Aplikasi (Hapus Nilai & Absensi)

Dokumen ini menjelaskan langkah-langkah untuk mereset data operasional aplikasi (nilai, absensi, dan log) sambil tetap mempertahankan data master seperti Siswa, Guru, dan Rombel yang sudah terisi.

## ⚠️ Peringatan Penting
**Lakukan Backup Database** sebelum menjalankan prosedur ini. Gunakan fitur Export pada phpMyAdmin untuk mengamankan data jika terjadi kesalahan.

---

## 1. Langkah Reset Data Operasional
Gunakan perintah SQL berikut pada tab **SQL** di phpMyAdmin untuk mengosongkan data transaksi:

```sql
-- Nonaktifkan pemeriksaan foreign key untuk sementara
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- RESET TABEL NILAI
-- ==========================================
TRUNCATE TABLE nilai_akademik;
TRUNCATE TABLE nilai_sumatif;
TRUNCATE TABLE nilai_proyek;
TRUNCATE TABLE nilai_komponen;
TRUNCATE TABLE nilai_tahfidz;
TRUNCATE TABLE nilai_ekskul;
TRUNCATE TABLE nilai_rapor;

-- ==========================================
-- RESET TABEL ABSENSI
-- ==========================================
TRUNCATE TABLE absensi_harian;
TRUNCATE TABLE rekap_absensi;

-- ==========================================
-- RESET TABEL CATATAN & KARAKTER
-- ==========================================
TRUNCATE TABLE setoran_tahfidz;
TRUNCATE TABLE catatan_akhlak;
TRUNCATE TABLE catatan_rapor;
TRUNCATE TABLE observasi_sikap;

-- ==========================================
-- RESET TABEL STATUS, LOG & NOTIFIKASI
-- ==========================================
TRUNCATE TABLE validasi_nilai;
TRUNCATE TABLE validasi_rapor_siswa;
TRUNCATE TABLE notifikasi;
TRUNCATE TABLE audit_logs;
TRUNCATE TABLE login_logs;
TRUNCATE TABLE riwayat_perubahan_nilai;

-- Aktifkan kembali pemeriksaan foreign key
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 2. Membersihkan Rombel Kosong (Opsional)
Jika terdapat rombel yang tidak memiliki siswa sama sekali dan ingin dihapus, gunakan perintah berikut:

```sql
DELETE FROM rombel 
WHERE id NOT IN (SELECT DISTINCT rombel_id FROM siswa WHERE rombel_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT rombel_id FROM anggota_rombel);
```

---

## Daftar Tabel yang Dipertahankan
Prosedur di atas **TIDAK MENGHAPUS** data berikut untuk menjaga struktur organisasi sekolah:

| Kategori | Nama Tabel | Keterangan |
| :--- | :--- | :--- |
| **Siswa** | `siswa`, `orangtua_wali` | Data identitas siswa dan wali tetap aman. |
| **Struktur** | `rombel`, `anggota_rombel` | Siswa tetap terdaftar di kelas masing-masing. |
| **Guru** | `guru_tendik`, `users` | Akun login dan data personil sekolah tetap ada. |
| **Akademik** | `mata_pelajaran`, `kurikulum` | Daftar mapel dan kurikulum tidak berubah. |
| **Mapping** | `guru_mapel`, `jadwal_pelajaran` | Penugasan guru dan jadwal mengajar tetap utuh. |
| **Wilayah** | `propinsi`, `kabupaten`, `kecamatan`, `desa` | Data wilayah untuk alamat tetap ada. |

---
*Terakhir diupdate: 2026-05-01*
