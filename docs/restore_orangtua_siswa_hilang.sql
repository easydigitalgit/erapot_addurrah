-- ================================================================
-- SCRIPT RESTORE DATA ORANG TUA / WALI SISWA (16 SISWA TERMASUK FIRZANA)
-- Sumber Data: Auto_Full_20261012_130712.sql
-- Keterangan: Memulihkan record orangtua_wali yang belum ter-restore
--             saat pemulihan siswa hilang sebelumnya.
-- ================================================================

SET FOREIGN_KEY_CHECKS=0;

-- 1. KELAS VII CARNELIAN
-- Siswa ID: 442 | Nama: FIRZANA AR RAQILLA EPSA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    432, 241, 442, 'APRYADI EPSA', '1986', 'D1-D3', 'Wiraswasta', 'Rp 2.000.000 - Rp 5.000.000', NULL,
    'CHAIRUN NISYAH', '1989', 'D1-D3', 'Karyawan Swasta', 'Rp 2.000.000 - Rp 5.000.000', NULL,
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, 'JL MARELAN VII KOMPLEK MASHAIRO NO B 16 LK V', '0'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 437 | Nama: Anggitta Lady Azzahra Siregar
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    427, 231, 437, 'Ady Affanri Siregar', '1976', 'D1-D3', 'PNS/TNI/Polri', 'Rp 2.000.000 - Rp 5.000.000', '1271130101760007',
    'Nursiah', '1977', 'SMA/Sederajat', '-', NULL, '1271125205770002',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '0'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);


-- 2. KELAS VII AMAZONITE
-- Siswa ID: 536 | Nama: AZKA ADHYASTHA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    506, 452, 536, 'DEDI ISMED SP', NULL, NULL, NULL, NULL, NULL,
    'SITI RAMLAH', NULL, NULL, NULL, NULL, NULL,
    NULL, NULL, NULL, NULL, NULL, NULL,
    NULL, 'JL MARELAN PSR. IV TIMUR GG DAMAI NO.3', '+62 852-6052-7113'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 538 | Nama: BIMA ADI BRATA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    508, 456, 538, '-', NULL, NULL, NULL, NULL, NULL,
    'SUMIRAH', NULL, NULL, NULL, NULL, NULL,
    NULL, NULL, NULL, NULL, NULL, NULL,
    NULL, '-', '+62 815-3342-9173'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 548 | Nama: MUHAMMAD FADHLAN IDRIS
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    518, 476, 548, 'MHD IDRIS', NULL, NULL, NULL, NULL, NULL,
    'NELYANA', NULL, NULL, NULL, NULL, NULL,
    NULL, NULL, NULL, NULL, NULL, NULL,
    NULL, 'JL. ABDUL SANI MUTALIB KOMP. TUTWURI HANDAYANI 4', '+62 812-6340-0207'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);


-- 3. KELAS VIII GRANIT
-- Siswa ID: 9 | Nama: BARIQ HIJRI AKBAR
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    99, NULL, 9, 'DENNY SYAHRIAL', '1986', 'S1', 'Wiraswasta', NULL, '1271020909860003',
    'LINA FADLIANA', '1984', 'S1', 'Wiraswasta', NULL, '1271195202840002',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '089613791966'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 11 | Nama: ELKAN ABSANA TANJUNG
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    124, NULL, 11, 'INDRA NAETHIE TANJUNG, SH', '1978', 'S1', 'Wiraswasta', NULL, '1271200407820006',
    'SARTIKA RAMADANI', '1988', NULL, 'Tidak bekerja', NULL, '1271135904880001',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '085261330994'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 12 | Nama: Fahrian Haikal Ginting
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    136, NULL, 12, 'Erianto Ginting', '1969', 'S1', 'Sudah Meninggal', NULL, '1271122702690003',
    'Inda Zulaika', '1981', NULL, 'Tidak bekerja', NULL, '1271124201810003',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '089629856752'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 14 | Nama: FATIH ELKARIM
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    150, NULL, 14, 'MUHARLEN', '1986', NULL, 'Karyawan Swasta', NULL, '1271102109860002',
    'DWI RIZKI PRIHATINI', '1986', 'S1', 'Lainnya', NULL, NULL,
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '08116260908'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);


-- 4. KELAS VIII TOPAZ
-- Siswa ID: 33 | Nama: ADABI LIMANSYAH SAGALA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    4, NULL, 33, 'BISMAR SAGALA', '1971', NULL, 'Nelayan', NULL, '1271082812710004',
    'ROSMA BR SOLIN', '1976', NULL, 'Tidak bekerja', NULL, '1271084510760003',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '082164094568'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 44 | Nama: Fathir Al Ghifary Lesmana
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    146, NULL, 44, 'Indra Lesmana, S.Pd', '1984', 'S1', 'Wiraswasta', NULL, '1271120803840001',
    'Yulia Syahfitri', '1988', NULL, 'Tidak bekerja', NULL, '1271128301880001',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '082364477598'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 45 | Nama: Ghaisan Agha Gautama
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    162, NULL, 45, 'Tri Muriono', '1984', 'S1', 'Lainnya', 'Rp. 2,000,000 - Rp. 4,999,999', '1203022805840008',
    'Asmaul Fadhillah', '1989', 'S1', 'Lainnya', 'Rp. 2,000,000 - Rp. 4,999,999', '1219035007890009',
    NULL, NULL, 'Tidak sekolah', NULL, NULL, NULL,
    NULL, NULL, '08887905951'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 47 | Nama: IQBAL AR ZAAKY
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    176, NULL, 47, 'SYAMSUL ARIFIN', '1978', NULL, 'Wiraswasta', NULL, '1271120807780002',
    'FAUZIAH', '1980', NULL, 'Tidak bekerja', NULL, '1271126707800003',
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '081260733003'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 48 | Nama: JAYA HARTOYO FAHRIAN
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    182, NULL, 48, '-', '0', NULL, '-', NULL, NULL,
    'FAUZIAH SIREGAR', '1981', NULL, 'Tidak bekerja', NULL, NULL,
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '+62 821-6486-6225'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 49 | Nama: M RIFFAT SYAH INDRA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    212, NULL, 49, 'RIANDI INDRA,SPd', '1977', 'S1', 'PNS/TNI/Polri', NULL, NULL,
    'MASITAH PASARIBU', '1980', 'S1', 'Tidak bekerja', NULL, NULL,
    '-', NULL, NULL, '-', NULL, NULL,
    NULL, NULL, '081361123692'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

-- Siswa ID: 50 | Nama: MHD DAYAN SANI ADMAJA
INSERT INTO `orangtua_wali` (
    `id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`,
    `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`,
    `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`,
    `email_ortu`, `alamat_orangtua`, `no_hp_ortu`
) VALUES (
    223, NULL, 50, 'IVAN SANI PARANA', '1981', 'S1', 'Wiraswasta', 'Rp. 2,000,000 - Rp. 4,999,999', '1271060211810002',
    'LINDA RAMAYANI, S.H', '1982', 'S1', 'Karyawan Swasta', 'Rp. 2,000,000 - Rp. 4,999,999', '1271124308820002',
    NULL, NULL, 'Tidak sekolah', NULL, NULL, NULL,
    NULL, NULL, '085260226277'
) ON DUPLICATE KEY UPDATE `siswa_id` = VALUES(`siswa_id`);

SET FOREIGN_KEY_CHECKS=1;
