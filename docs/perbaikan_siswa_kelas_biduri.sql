-- ==========================================================================
-- SCRIPT SQL: PERBAIKAN SISWA KELAS IX BIDURI (ROMBEL ID: 69)
-- Sumber Referensi: Data Siswa Dapodik Perkelas TA 2026-2027.xlsx & DB Lokal
-- Tanggal Dibuat  : 26 Oktober 2026
-- Database Target : raporsmpit (VPS)
-- ==========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. RESTORE / UPDATE 6 SISWI KE MASTER SISWA IX BIDURI (ROMBEL ID: 69)
INSERT INTO `siswa` (
    `id`, `user_id`, `rombel_id`, `nis`, `nisn`, `nik`, `nama_lengkap`, `jenis_kelamin`, 
    `tempat_lahir`, `tanggal_lahir`, `agama`, `anak_ke`, `alamat_siswa`, `rt`, `rw`, 
    `kelurahan`, `kecamatan`, `kode_pos`, `jenis_tinggal`, `alat_transportasi`, 
    `no_hp`, `asal_sekolah`, `penerima_kps`, `penerima_kip`, `layak_pip`, `kebutuhan_khusus`, 
    `status_siswa`, `berat_badan`, `tinggi_badan`, `jml_saudara_kandung`, `jarak_ke_sekolah`, 
    `ekskul_1`, `ekskul_2`
) VALUES
(
    196, NULL, 69, '08.24.0023', '0128407359', '1271104403120003', 'Asyha Faradiba', 'P', 
    'Medan', '2012-03-04', 'Islam', '2', 'Jl. Sidomulyo no. 74 LK. XXIV', '0', '0', 
    'Desa/Kel. Tanjung Mulia', 'Kec. Medan Deli', '20241', 'Bersama orang tua', 'Sepeda motor', 
    '081370887847', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 37, 142, 1, 5, 1, NULL
),
(
    197, NULL, 69, '08.24.0024', '0127164687', '1271126102120001', 'ASYIFA NUR UMMAIMA', 'P', 
    'MEDAN', '2012-02-21', 'Islam', '1', 'DUSUN XIV PELITA', '0', '0', 
    'Desa/Kel. Medan Sinembah', 'Kec. Tanjung Morawa', '20362', 'Bersama orang tua', 'Sepeda motor', 
    '081262695839', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 37, 144, 1, 5, 1, NULL
),
(
    198, NULL, 69, '08.24.0025', '0118113038', '1271046009110001', 'ASYIFA ULHUSNA', 'P', 
    'KUALA LANGKAT', '2011-09-20', 'Islam', '1', 'GURAMI NO. 344 AA GRIYA MARTUBUNG', '0', '0', 
    'Desa/Kel. Tangkahan', 'Kec. Medan Labuhan', '20251', 'Bersama orang tua', 'Sepeda motor', 
    '085264510702', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 33, 141, 1, 5, 1, NULL
),
(
    201, NULL, 69, '08.24.0037', '3129480751', '1271126601120004', 'CYNTHIA ADZKANA AINIYAHDEWI HASIBUAN', 'P', 
    'SURABAYA', '2012-01-26', 'Islam', '1', 'titi papan', '0', '0', 
    'Desa/Kel. Titi Papan', 'Kec. Medan Deli', '20244', 'Bersama orang tua', 'Sepeda motor', 
    '0', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 31, 137, 1, 4, 1, NULL
),
(
    204, NULL, 69, '08.24.0050', '3122170198', '1271126709120004', 'FATIMA AZZAHRA', 'P', 
    'MEDAN', '2012-09-27', 'Islam', '2', 'JL TITI PAHLAWAN GANG SAPAR NO 8', '0', '0', 
    'Desa/Kel. Rengas Pulau', 'Kec. Medan Marelan', '20255', 'Bersama orang tua', 'Sepeda motor', 
    '081224118147', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 31, 135, 1, 5, 1, NULL
),
(
    206, NULL, 69, '08.24.0059', '3127061157', '1271134204120003', 'HAFIZHA QUINSYA SEMBIRING', 'P', 
    'MEDAN', '2012-04-02', 'Islam', '1', 'Martubung - Kec. Medan Labuhan', '0', '0', 
    'Martubung', 'Kec. Medan Labuhan', NULL, 'Bersama orang tua', 'Sepeda motor', 
    '0', 'SD SWASTA ISLAM TERPADU AD-DURRAH', 'Tidak', 'Tidak', 'Tidak', 'Tidak ada', 
    'Aktif', 28, 139, 1, 6, 1, NULL
)
ON DUPLICATE KEY UPDATE `rombel_id` = 69, `status_siswa` = 'Aktif';

-- 2. DAFTARKAN / PERBARUI KE ANGGOTA_ROMBEL SEMESTER AKTIF (TA: 10 / GANJIL 2026-2027)
INSERT INTO `anggota_rombel` (`siswa_id`, `rombel_id`, `tahun_ajaran_id`, `semester`)
VALUES 
(196, 69, 10, 'Ganjil'),
(197, 69, 10, 'Ganjil'),
(198, 69, 10, 'Ganjil'),
(201, 69, 10, 'Ganjil'),
(204, 69, 10, 'Ganjil'),
(206, 69, 10, 'Ganjil')
ON DUPLICATE KEY UPDATE `rombel_id` = 69;

-- 3. RESTORE DATA ORANG TUA / WALI
INSERT INTO `orangtua_wali` (
    `id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pekerjaan_ayah`, `nik_ayah`, 
    `nama_ibu`, `tahun_lahir_ibu`, `pekerjaan_ibu`, `nik_ibu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES
(69, 196, 'Faried Rusady', '1983', 'Karyawan Swasta', '1271100901830003', 'Sri Rahayu', '1986', 'Karyawan Swasta', NULL, 'Jl. Sidomulyo no. 74 LK. XXIV', '081370887847'),
(71, 197, 'FAJAR SAH PUTRA', '1995', 'Wiraswasta', '1271191004950001', 'SITI RADIAH', '1994', 'Wiraswasta', '1271124611940002', 'DUSUN XIV PELITA', '081262695839'),
(72, 198, 'MASPARDI HUTAGALUNG, ST', '1986', 'Karyawan Swasta', '1271040208860012', 'LIA RAMADANI', '1990', 'Tidak bekerja', NULL, 'GURAMI NO. 344 AA GRIYA MARTUBUNG', '085264510702'),
(109, 201, '-', '0', 'Karyawan Swasta', NULL, 'KARTIKA DEWI WINDASARI', '0', 'Tidak bekerja', NULL, 'titi papan', '0'),
(152, 204, 'DRS. DANAN DJAJA, MA', '1952', 'Lainnya', '1271120911520001', 'LAILATU SYAKDIAH', '1974', 'Lainnya', '1271124903740004', 'JL TITI PAHLAWAN GANG SAPAR NO 8', '081224118147'),
(169, 206, '-', '0', 'Wiraswasta', NULL, 'DESWITA SARI LUBIS, SH', '0', 'Tidak bekerja', NULL, 'Martubung - Kec. Medan Labuhan', '0')
ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`), `nama_ayah` = VALUES(`nama_ayah`), `nama_ibu` = VALUES(`nama_ibu`), `no_hp_ortu` = VALUES(`no_hp_ortu`);

SET FOREIGN_KEY_CHECKS = 1;

-- 4. CEK TOTAL SISWA AKTIF KELAS IX BIDURI DI VPS (HARUS TEPAT 30 SISWA AKTIF)
SELECT COUNT(*) AS total_siswa_aktif_biduri FROM siswa WHERE rombel_id = 69 AND status_siswa = 'Aktif';
