-- ==========================================================================
-- SCRIPT SQL: VERIFIKASI & RESTORE SISWA MUHAMMAD AKBAR WIJAYA (KELAS VIII GRANIT)
-- Database Target : raporsmpit (VPS & Local)
-- ==========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Pastikan Data Siswa ada di tabel siswa dengan rombel_id = 72 (VIII Granit) & Status Aktif
INSERT INTO `siswa` (
    `id`, `user_id`, `rombel_id`, `nis`, `nisn`, `nik`, `nama_lengkap`, `email_siswa`, `jenis_kelamin`, 
    `tempat_lahir`, `tanggal_lahir`, `agama`, `status_dalam_keluarga`, `anak_ke`, `alamat_siswa`, 
    `rt`, `rw`, `dusun`, `kelurahan`, `kecamatan`, `kode_pos`, `jenis_tinggal`, `alat_transportasi`, 
    `no_telp_rumah`, `no_hp`, `asal_sekolah`, `skhun`, `penerima_kps`, `no_kps`, `no_peserta_un`, 
    `no_seri_ijazah`, `penerima_kip`, `nomor_kip`, `nama_di_kip`, `nomor_kks`, `no_registrasi_akta`, 
    `layak_pip`, `alasan_layak_pip`, `kebutuhan_khusus`, `no_kk`, `diterima_dikelas`, `tgl_diterima`, 
    `status_siswa`, `berat_badan`, `tinggi_badan`, `lingkar_kepala`, `jml_saudara_kandung`, 
    `jarak_ke_sekolah`, `ekskul_1`, `ekskul_2`, `ekskul_3`, `foto_siswa`
) VALUES (
    '18', NULL, '72', '09.25.0079', '3136848936', '1271120103130006', 'Muhammad Akbar Wijaya', NULL, 'L', 
    'Medan', '2013-03-01', 'Islam', NULL, '1', 'Jl. Coklat LK. 09', 
    '0', '0', NULL, 'Paya Pasir', 'Kec. Medan Marelan', NULL, 'Bersama orang tua', 'Jalan kaki', 
    NULL, '082166207536', 'UPT SD NEGERI 067262', NULL, 'Tidak', NULL, NULL, 
    NULL, 'Tidak', NULL, NULL, NULL, '1271-LT-06092013-0402', 
    'Tidak', NULL, 'Tidak ada', '1271122311050018', NULL, '2026-10-12', 
    'Aktif', '41', '145', '0', '0', 
    '5', '17', NULL, NULL, '1780457580_fd457ef9e06ca60e9363.webp'
) 
ON DUPLICATE KEY UPDATE 
    `rombel_id` = '72', 
    `status_siswa` = 'Aktif',
    `nik` = '1271120103130006',
    `nisn` = '3136848936',
    `nis` = '09.25.0079';

-- 2. Pastikan Keanggotaan Rombel ada di tabel anggota_rombel untuk TA 2026/2027 Ganjil (TA ID: 10)
INSERT INTO `anggota_rombel` (`id`, `siswa_id`, `rombel_id`, `tahun_ajaran_id`, `semester`, `created_at`) 
VALUES ('591', '18', '72', '10', 'Ganjil', '2026-09-03 14:40:41') 
ON DUPLICATE KEY UPDATE 
    `rombel_id` = '72', 
    `tahun_ajaran_id` = '10', 
    `semester` = 'Ganjil';

-- 3. Pastikan Data Orang Tua ada di tabel orangtua_wali
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, 
    `penghasilan_ayah`, `nik_ayah`, `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, 
    `penghasilan_ibu`, `nik_ibu`, `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, 
    `penghasilan_wali`, `nik_wali`, `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    '234', NULL, '18', 'Teguh Triadi', '1985', NULL, 'Karyawan Swasta', 
    NULL, '1271063112850003', 'Rusdiana', '1979', NULL, 'Tidak bekerja', 
    NULL, '1271127006790001', '-', NULL, NULL, '-', NULL, NULL, NULL, 'Jl. Coklat LK. 09', '082166207536'
) 
ON DUPLICATE KEY UPDATE 
    `siswa_id` = '18',
    `nama_ayah` = VALUES(`nama_ayah`), 
    `nama_ibu` = VALUES(`nama_ibu`),
    `no_hp_ortu` = VALUES(`no_hp_ortu`);

SET FOREIGN_KEY_CHECKS = 1;
