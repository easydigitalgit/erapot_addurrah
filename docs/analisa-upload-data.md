# Analisa Proses Upload/Import Data - Potensi Penghapusan Siswa

## Ringkasan
Proses import siswa (`SiswaController::import`) **tidak** menghapus data siswa. Ia hanya INSERT/UPDATE berdasarkan pencocokan NIS/NISN/NIK/nama+tempat lahir. Tidak ada `DELETE FROM siswa` di alur import.

## Penyebab penurunan jumlah siswa (29 -> 24)
Penyebab utama adalah **Restore database penuh** (`BackupController::restore`):
- Restore mengeksekusi dump SQL mentah ke DB aktif, termasuk `DROP TABLE`/`INSERT` massal tanpa transaksi.
- Jika file backup yang di-restore adalah snapshot lama (mis. backup otomatis harian), data siswa yang ditambahkan setelah snapshot hilang.
- Tidak ada validasi versi/timestamp; restore menimpa seluruh data.

## Alur berisiko lain
1. `BackupController::restore` (upload file SQL / pilih backup) -> timpa total DB.
2. Migrasi user (`MigrateUsers`, `UsersController`) -> update `user_id` relasi siswa; tidak hapus, tapi bisa putus relasi jika tidak konsisten.
3. `TingkatRombel` import -> memindah siswa antar rombel; tidak hapus, tapi bisa salah mapping.

## Rekomendasi
- Jangan restore backup penuh untuk sinkron VPS->lokal; gunakan export/import siswa terbatas atau sync incremental.
- Tambahkan timestamp/versi pada backup dan cegah restore backup lebih lama dari data aktif.
- Audit log perubahan jumlah siswa per rombel.
- Backup sebelum restore otomatis.
