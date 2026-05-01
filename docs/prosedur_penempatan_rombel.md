# Analisa & Prosedur Penempatan Kelas Siswa (Rombel)

Dokumen ini menjelaskan mekanisme penempatan kelas (rombel) siswa dalam sistem **erapoteasy** untuk memastikan integritas data akademik (nilai dan rapor).

## Struktur Data Penempatan Kelas
Sistem menggunakan dua mekanisme untuk mencatat penempatan kelas siswa:
*   **Tabel `siswa` (Kolom `rombel_id`)**: Mencatat kelas "saat ini" yang melekat pada profil siswa.
*   **Tabel `anggota_rombel` (Mesin Waktu)**: Mencatat keanggotaan siswa di suatu kelas pada **Tahun Ajaran & Semester tertentu**. Tabel ini digunakan sebagai sumber data utama (Source of Truth) untuk proses akademik seperti nilai dan rapor.

## Temuan Analisa
Berdasarkan audit kode pada `SiswaController.php` dan `TingkatRombelController.php`:

### 1. Resiko Edit Langsung (Data Siswa)
Secara teknis, Admin dapat mengubah kelas melalui menu **Edit Siswa**. Namun, tindakan ini **SANGAT TIDAK DISARANKAN** karena:
*   **Inkonsistensi Data**: Fitur *Edit Siswa* (pada `SiswaController::update`) hanya memperbarui kolom `rombel_id` di tabel `siswa`. Fitur ini **tidak memperbarui** data di tabel `anggota_rombel`.
*   **Efek Domino**: Jika data `anggota_rombel` tidak sinkron, maka:
    *   Siswa tersebut **tidak akan muncul** di Monitoring Nilai.
    *   Siswa tersebut **tidak akan muncul** di Validasi Nilai.
    *   **Rapor tidak bisa dicetak** karena sistem tidak menemukan siswa tersebut sebagai anggota kelas di tahun ajaran aktif pada tabel `anggota_rombel`.

### 2. Prevalensi `anggota_rombel`
Sistem (misal pada `getAll()` siswa) menggunakan logika `COALESCE(ar.rombel_id, s.rombel_id)`. Artinya, jika ada data di `anggota_rombel` untuk tahun ajaran aktif, data tersebutlah yang akan digunakan oleh sistem, bukan data di tabel profil siswa.

---

## Prosedur Standar Pemindahan Kelas
Untuk menjaga integritas data, pemindahan kelas harus dilakukan melalui prosedur resmi di menu **Tingkat & Rombel**:

| Prosedur | Menu / Fitur | Logika Sistem |
| :--- | :--- | :--- |
| **Menambah Siswa ke Kelas** | Tingkat & Rombel > Pilih Kelas > Tambah Siswa | Memperbarui `siswa.rombel_id` DAN menyisipkan record baru ke `anggota_rombel`. |
| **Memindahkan Siswa** | Tingkat & Rombel > Pilih Kelas > Pindahkan Siswa | Memperbarui `siswa.rombel_id` DAN memperbarui record yang ada di `anggota_rombel`. |
| **Mengeluarkan Siswa** | Tingkat & Rombel > Pilih Kelas > Hapus dari Rombel | Mengosongkan `siswa.rombel_id` DAN menghapus record di `anggota_rombel` untuk periode aktif. |
| **Kenaikan Kelas/Lulus** | Tingkat & Rombel > Fitur Migrasi | Memproses promosi kelas atau kelulusan secara massal dengan rekam riwayat baru. |

---

## Kesimpulan & Rekomendasi
Admin harus memprioritaskan penggunaan menu **Tingkat & Rombel** untuk manajemen kelas. 

> [!IMPORTANT]
> Jangan melakukan pemindahan kelas melalui edit data siswa secara langsung jika tahun ajaran sudah berjalan dan proses input nilai sudah dimulai.
