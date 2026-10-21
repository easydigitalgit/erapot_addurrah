-- ==========================================================================
-- SCRIPT SQL: PERBAIKAN SISWA KELAS IX FAIRUZ & RESTORE KOLOM DATA TERGESER
-- Sumber Referensi: Data Siswa Dapodik Perkelas TA 2026-2027.xlsx
-- Wali Kelas      : Mohammad Hamsyahri, S.Pd., Gr.
-- Tanggal Dibuat  : 21 Oktober 2026
-- Database Target : raporsmpit (VPS & Local)
-- ==========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================================================
-- BAGIAN 1: RESTORE 5 SISWA HILANG KELAS IX FAIRUZ (ID: 165, 167, 168, 169, 170)
--           Mengembalikan siswa ke tabel `siswa` & data orang tua ke `orangtua_wali`.
--           Karena record `anggota_rombel` & nilai formatif/rapor di DB masih utuh,
--           siswa langsung kembali ke rombel IX Fairuz (genap 28 siswa aktif).
-- ==========================================================================

-- 1.1 Restore ke Tabel siswa
INSERT INTO `siswa` (`id`, `user_id`, `rombel_id`, `nis`, `nisn`, `nik`, `nama_lengkap`, `email_siswa`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `status_dalam_keluarga`, `anak_ke`, `alamat_siswa`, `rt`, `rw`, `dusun`, `kelurahan`, `kecamatan`, `kode_pos`, `jenis_tinggal`, `alat_transportasi`, `no_telp_rumah`, `no_hp`, `asal_sekolah`, `skhun`, `penerima_kps`, `no_kps`, `no_peserta_un`, `no_seri_ijazah`, `penerima_kip`, `nomor_kip`, `nama_di_kip`, `nomor_kks`, `no_registrasi_akta`, `layak_pip`, `alasan_layak_pip`, `kebutuhan_khusus`, `no_kk`, `diterima_dikelas`, `tgl_diterima`, `status_siswa`, `berat_badan`, `tinggi_badan`, `lingkar_kepala`, `jml_saudara_kandung`, `jarak_ke_sekolah`, `ekskul_1`, `ekskul_2`, `ekskul_3`, `foto_siswa`) VALUES
('165', NULL, '67', '08.24.0035', '0117135026', '1271083010110002', 'CHALVIN SURBAKTI', NULL, 'L', 'Medan', '2011-10-30', 'Islam', NULL, '1', 'Jl. Marelan V Pasar 2 Barat Gg. Arjuna No. 11 A', '0', '0', NULL, 'Terjun', 'Kec. Medan Marelan', NULL, 'Bersama orang tua', 'Ojek', NULL, '088262276162', 'SDS NUR FADHILAH', NULL, 'Tidak', NULL, '-', NULL, 'Tidak', NULL, '1', NULL, '1271-LT-17042012-0027', 'Tidak', 'Sudah Mampu', 'Tidak ada', NULL, NULL, NULL, 'Aktif', '31', '133', '0', '1', '4', '1', '10', '8', NULL),
('167', NULL, '67', '08.24.0038', '0125883226', '1271062603130001', 'DAFFA ARYA GHADZAN', NULL, 'L', 'MEDAN', '2013-03-26', 'Islam', NULL, '1', 'Medan Deli', NULL, NULL, NULL, 'Titi Papan', 'Kec. Medan Deli', NULL, 'Bersama orang tua', 'Sepeda motor', NULL, NULL, NULL, NULL, 'Tidak', NULL, NULL, NULL, 'Tidak', NULL, '1', NULL, NULL, 'Tidak', NULL, 'Tidak ada', NULL, NULL, NULL, 'Aktif', '34', '137', '0', '1', '7', '17', '8', NULL, NULL),
('168', NULL, '67', '08.24.0042', '0127399258', '1271201310120001', 'EGA PRABUDI', NULL, 'L', 'MEDAN', '2012-10-13', 'Islam', NULL, '1', 'JL MARELAN I KOMP GRIYA BESTARI PERMAI BLOK E11', '0', '0', NULL, 'Desa/Kel. T E R J U N', 'Kec. Medan Marelan', '20256', 'Bersama orang tua', 'Sepeda motor', NULL, '081375600777', 'SD SWASTA ISLAM TERPADU AD-DURRAH', NULL, 'Tidak', NULL, NULL, NULL, 'Tidak', NULL, NULL, NULL, '1271-LT-13022013-0012', 'Tidak', 'Sudah Mampu', 'Tidak ada', NULL, NULL, NULL, 'Aktif', '30', '139', '0', '1', '4', '1', '14', '8', NULL),
('169', NULL, '67', '08.24.0043', '0122285018', '1271140803120001', 'ENZO ALFERD ZIDAN', NULL, 'L', 'MEDAN', '2012-03-08', 'Islam', NULL, '1', 'Tanah Enam Ratus - Kec. Medan Marelan', NULL, NULL, NULL, 'Desa/Kel. Tanah Enam Ratus', 'Kec. Medan Marelan', NULL, 'Bersama orang tua', 'Sepeda motor', NULL, NULL, NULL, NULL, 'Tidak', NULL, NULL, NULL, 'Tidak', NULL, '1', NULL, NULL, 'Tidak', NULL, 'Tidak ada', NULL, NULL, NULL, 'Aktif', '33', '141', '0', '1', '7', '17', '8', NULL, NULL),
('170', NULL, '67', '08.24.0046', '3122288457', '1271062008120002', 'EZA SAPITRA ADITYA', NULL, 'L', 'MEDAN', '2012-08-20', 'Islam', NULL, '1', 'terjun', NULL, NULL, NULL, 'Desa/Kel. T E R J U N', 'Kec. Medan Marelan', NULL, 'Bersama orang tua', 'Sepeda motor', NULL, NULL, NULL, NULL, 'Tidak', NULL, NULL, NULL, 'Tidak', NULL, '1', NULL, NULL, 'Tidak', NULL, 'Tidak ada', NULL, NULL, NULL, 'Aktif', '31', '133', '0', '1', '4', '3', '8', NULL, NULL)
ON DUPLICATE KEY UPDATE `rombel_id` = '67', `status_siswa` = 'Aktif';

-- 1.2 Restore Data Orang Tua ke Tabel orangtua_wali
INSERT INTO `orangtua_wali` (`id`, `user_id`, `siswa_id`, `nama_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ayah`, `nama_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_ibu`, `nama_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nik_wali`, `email_ortu`, `alamat_orangtua`, `no_hp_ortu`) VALUES
('103', NULL, '165', 'Deden Sopian', '1982', NULL, 'Karyawan Swasta', NULL, '1207041502820002', 'Ulfa Yenny', '1985', NULL, 'Tidak bekerja', NULL, '1271205610850002', '-', NULL, NULL, '-', NULL, NULL, NULL, NULL, '088262276162'),
('112', NULL, '167', '-', '0', NULL, 'Wiraswasta', NULL, NULL, 'CITRA RIZDKIKA WULANDARI', '0', NULL, 'Tidak bekerja', NULL, NULL, '-', NULL, NULL, '-', NULL, NULL, NULL, NULL, '08116000809'),
('122', NULL, '168', 'BUDI ANTO, SH', '1980', 'S1', 'PNS/TNI/Polri', NULL, '1271200609800006', 'NINA AFRIANY', '1986', 'S1', 'Karyawan Swasta', NULL, '1271124404860001', '-', NULL, NULL, '-', NULL, NULL, NULL, NULL, '081375600777'),
('127', NULL, '169', '-', '0', NULL, 'Karyawan Swasta', NULL, NULL, 'RINI BUDI YANTI', '0', NULL, 'Tidak bekerja', NULL, NULL, '-', NULL, NULL, '-', NULL, NULL, NULL, NULL, '081325977400'),
('131', NULL, '170', '-', '0', NULL, 'Wiraswasta', NULL, NULL, 'SEPRIYANI', '0', NULL, 'Tidak bekerja', NULL, NULL, '-', NULL, NULL, '-', NULL, NULL, NULL, NULL, '081260251260')
ON DUPLICATE KEY UPDATE `nama_ayah` = VALUES(`nama_ayah`), `nama_ibu` = VALUES(`nama_ibu`), `no_hp_ortu` = VALUES(`no_hp_ortu`);

-- ==========================================================================
-- BAGIAN 2: PERBAIKAN KOLOM DATA TERGESER KHUSUS KELAS IX FAIRUZ (28 SISWA)
--           Memperbaiki NIK, Kelurahan, Kecamatan, No HP, Alamat, dan Asal Sekolah
-- ==========================================================================

-- Siswa ID 163: Arsya Althaf Abyan (NISN: 0129329730)
UPDATE `siswa` SET `nik` = '1271021411120001', `kelurahan` = 'Desa/Kel. Sunggal', `kecamatan` = 'Kec. Medan Sunggal', `no_hp` = '081215604949', `alamat_siswa` = 'Jl. P. Baris Komplek Imperium Blok E No.3A', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '163';
-- Siswa ID 164: AULIA ARBIE (NISN: 3120665879)
UPDATE `siswa` SET `nik` = '1271121302120005', `kelurahan` = 'Desa/Kel. Labuhan Deli', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = NULL, `alamat_siswa` = 'Labuhan Deli - Kec. Medan Marelan', `asal_sekolah` = '' WHERE `id` = '164';
-- Siswa ID 165: CHALVIN SURBAKTI (NISN: 0117135026)
UPDATE `siswa` SET `nik` = '1271083010110002', `kelurahan` = 'Terjun', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '088262276162', `alamat_siswa` = 'Jl. Marelan V Pasar 2 Barat Gg. Arjuna No. 11 A', `asal_sekolah` = 'SDS NUR FADHILAH' WHERE `id` = '165';
-- Siswa ID 166: DAFFA AHZA DANISH (NISN: 0121762297)
UPDATE `siswa` SET `nik` = '1271062112120004', `kelurahan` = 'Desa/Kel. Labuhan Deli', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '082273794115', `alamat_siswa` = 'JL. MARELAN II PASAR IV TIMUR', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '166';
-- Siswa ID 167: DAFFA ARYA GHADZAN (NISN: 0125883226)
UPDATE `siswa` SET `nik` = '1271062603130001', `kelurahan` = 'Titi Papan', `kecamatan` = 'Kec. Medan Deli', `no_hp` = NULL, `alamat_siswa` = 'Medan Deli', `asal_sekolah` = '' WHERE `id` = '167';
-- Siswa ID 168: EGA PRABUDI (NISN: 0127399258)
UPDATE `siswa` SET `nik` = '1271201310120001', `kelurahan` = 'Desa/Kel. T E R J U N', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081375600777', `alamat_siswa` = 'JL MARELAN I KOMP GRIYA BESTARI PERMAI BLOK E11', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '168';
-- Siswa ID 169: ENZO ALFERD ZIDAN (NISN: 0122285018)
UPDATE `siswa` SET `nik` = '1271140803120001', `kelurahan` = 'Desa/Kel. Tanah Enam Ratus', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = NULL, `alamat_siswa` = 'Tanah Enam Ratus - Kec. Medan Marelan', `asal_sekolah` = '' WHERE `id` = '169';
-- Siswa ID 170: EZA SAPITRA ADITYA (NISN: 3122288457)
UPDATE `siswa` SET `nik` = '1271062008120002', `kelurahan` = 'Desa/Kel. T E R J U N', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = NULL, `alamat_siswa` = 'terjun', `asal_sekolah` = '' WHERE `id` = '170';
-- Siswa ID 171: FATIH AL HAKIM AZUAR (NISN: 3121442308)
UPDATE `siswa` SET `nik` = '1271120506120001', `kelurahan` = 'LABUHAN DELI', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081360052533', `alamat_siswa` = 'JLN. YONG PANAH HIJAU GG. DARUL IKHLAS', `asal_sekolah` = 'SDS DR.WAHIDIN SUDIROHUSODO' WHERE `id` = '171';
-- Siswa ID 172: Gibran Al Khalifi Zikri (NISN: 3123496851)
UPDATE `siswa` SET `nik` = '1271123010120008', `kelurahan` = 'Rengas Pulau', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081396525776', `alamat_siswa` = 'Jl. Marelan II Gg. Swadaya', `asal_sekolah` = 'SDS AL-FAJAR' WHERE `id` = '172';
-- Siswa ID 173: HANIFAN AL WAFI (NISN: 3125848513)
UPDATE `siswa` SET `nik` = '1271121804120001', `kelurahan` = 'Desa/Kel. T E R J U N', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = NULL, `alamat_siswa` = 'T E R J U N - Kec. Medan Marelan', `asal_sekolah` = '' WHERE `id` = '173';
-- Siswa ID 174: MARAFA ANANDA (NISN: 0126872877)
UPDATE `siswa` SET `nik` = '1271130805120003', `kelurahan` = 'KLAMBIR', `kecamatan` = 'Kec. Hamparan Perak', `no_hp` = NULL, `alamat_siswa` = 'DUSUN III DESA KLAMBIR', `asal_sekolah` = 'UPT SPF SD NEGERI 101744 DESA KLAMBIR' WHERE `id` = '174';
-- Siswa ID 175: MHD ADITHYA SARAGIH (NISN: 3125836474)
UPDATE `siswa` SET `nik` = '1271132210120001', `kelurahan` = 'Tangkahan', `kecamatan` = 'Kec. Medan Labuhan', `no_hp` = NULL, `alamat_siswa` = 'Tangkahan - Kec. Medan Labuhan', `asal_sekolah` = '' WHERE `id` = '175';
-- Siswa ID 176: MUHAMMAD AZKAR (NISN: 3125095342)
UPDATE `siswa` SET `nik` = '1271080309120004', `kelurahan` = 'Belawan I', `kecamatan` = 'Kec. Medan Kota Belawan', `no_hp` = NULL, `alamat_siswa` = 'Belawan I', `asal_sekolah` = '' WHERE `id` = '176';
-- Siswa ID 177: MUHAMMAD DAIROBI ALFATH (NISN: 3123754728)
UPDATE `siswa` SET `nik` = '1271080204120001', `kelurahan` = 'Desa/Kel. Belawan Bahagia', `kecamatan` = 'Kec. Medan Kota Belawan', `no_hp` = '081260454933', `alamat_siswa` = 'LORONG PERHATIAN NO 54 LK 16', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '177';
-- Siswa ID 178: MUHAMMAD FAHRI RAMADHAN (NISN: 3127255237)
UPDATE `siswa` SET `nik` = '1271122507120001', `kelurahan` = 'Desa/Kel. T E R J U N', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081397788708', `alamat_siswa` = 'LINGK 04 PASAR III', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '178';
-- Siswa ID 179: MUHAMMAD NALA AL FARIZI (NISN: 0128508311)
UPDATE `siswa` SET `nik` = '1271200403120001', `kelurahan` = 'Rengas Pulau', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '087805140350', `alamat_siswa` = 'MANGAAN VII GG KELUARGA', `asal_sekolah` = 'SDS BRIGJEND KATAMSO II' WHERE `id` = '179';
-- Siswa ID 180: MUHAMMAD RADITYA AZRI (NISN: 3114761315)
UPDATE `siswa` SET `nik` = '1271082211110004', `kelurahan` = 'Desa/Kel. Tanah Enam Ratus', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '085270735399', `alamat_siswa` = 'JL MARELAN RAYA GANG LURAH', `asal_sekolah` = 'SD SWASTA ISLAM TERPADU AD-DURRAH' WHERE `id` = '180';
-- Siswa ID 181: MUKHLIS (NISN: 0126631828)
UPDATE `siswa` SET `nik` = '1271200610120001', `kelurahan` = 'Terjun', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '08989981114', `alamat_siswa` = 'Jl.Kapt.rahmad Budin', `asal_sekolah` = 'SDS NUR FADHILAH' WHERE `id` = '181';
-- Siswa ID 182: OK. Zabran Rizky (NISN: 0123577127)
UPDATE `siswa` SET `nik` = '1271061902120001', `kelurahan` = 'Desa/Kel. Rengas Pulau', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081376222609', `alamat_siswa` = 'Jl. Maharani II No. 35 Lk. XVIII', `asal_sekolah` = 'SDS PERTIWI' WHERE `id` = '182';
-- Siswa ID 183: Qori Al Furqan Tambunan (NISN: 0129774943)
UPDATE `siswa` SET `nik` = '1271121909120004', `kelurahan` = 'Rengas Pulau', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '088260096791', `alamat_siswa` = 'Jl. Marelan Raya Psr. 5 Lingk. 6', `asal_sekolah` = 'UPT SD NEGERI 064009' WHERE `id` = '183';
-- Siswa ID 184: RAFA EL FATHARA (NISN: 0123402924)
UPDATE `siswa` SET `nik` = '1271202203120002', `kelurahan` = 'RENGAS PULAU', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '081264283313', `alamat_siswa` = 'GRAND PURI MARELAN PASAR 3 TIMUR', `asal_sekolah` = 'SDS PLUS MIP MARELAN' WHERE `id` = '184';
-- Siswa ID 185: RASYA MUHAMMAD ATHAYA (NISN: 0121342803)
UPDATE `siswa` SET `nik` = '1207253101120002', `kelurahan` = 'Helvetia', `kecamatan` = 'Kec. Labuhan Deli', `no_hp` = '081376987699', `alamat_siswa` = 'Jl. Veteran Pasar VII Gg. Peristiwa Dusun IV', `asal_sekolah` = 'SD PANGERAN ANTASARI' WHERE `id` = '185';
-- Siswa ID 186: RAZKA SAQIL ALVARO (NISN: 0124159788)
UPDATE `siswa` SET `nik` = '1271121207120003', `kelurahan` = 'PAYA PASIR', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '082164058181', `alamat_siswa` = 'GG. MUSHOLLA LK. 01', `asal_sekolah` = 'SDS DR.WAHIDIN SUDIROHUSODO' WHERE `id` = '186';
-- Siswa ID 187: RIFKI ZULKARNAIN NASUTION (NISN: 3116848505)
UPDATE `siswa` SET `nik` = '1274040907120001', `kelurahan` = 'Pematang Pasir', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '082167234984', `alamat_siswa` = 'JL. TERISI LK. II', `asal_sekolah` = 'SDS BINA TARUNA 3' WHERE `id` = '187';
-- Siswa ID 188: RIZKY SAPUTRA (NISN: 0129509131)
UPDATE `siswa` SET `nik` = '1271061501120004', `kelurahan` = 'KOTA BANGUN', `kecamatan` = 'Kec. Medan Deli', `no_hp` = '082361390629', `alamat_siswa` = 'JLN. PERUNGGU DALAM NO. II-C LINGK. V KOTA BANGUN', `asal_sekolah` = 'SDS DR.WAHIDIN SUDIROHUSODO' WHERE `id` = '188';
-- Siswa ID 189: ZAHRAN EMRY KADITYA (NISN: 0127233999)
UPDATE `siswa` SET `nik` = '1207262902120015', `kelurahan` = 'RENGAS PULAU', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '082297306217', `alamat_siswa` = 'PSR 3 TIMUR Gg. SEROJA', `asal_sekolah` = 'SDS BRIGJEND KATAMSO II' WHERE `id` = '189';
-- Siswa ID 190: ZEIN AHMAD IBRAHIM (NISN: 0126894063)
UPDATE `siswa` SET `nik` = '1271120806120001', `kelurahan` = 'Tanah Enam Ratus', `kecamatan` = 'Kec. Medan Marelan', `no_hp` = '082365698303', `alamat_siswa` = 'Jl. Paku No.74 Lingkungan IX', `asal_sekolah` = 'SDS ISLAM AL-HUDA' WHERE `id` = '190';

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================================================
-- BAGIAN 3: QUERY VERIFIKASI SETELAH EKSEKUSI DI VPS
-- ==========================================================================
-- 3.1 Cek Total Siswa Aktif Kelas IX Fairuz (Harus Tepat 28 Siswa)
SELECT COUNT(*) AS total_fairuz FROM siswa WHERE rombel_id = 67 AND status_siswa = 'Aktif';

-- 3.2 Cek Total Siswa yang Tampil di Dashboard Wali Kelas IX Fairuz (Harus Tepat 28 Siswa)
SELECT COUNT(s.id) AS total_tampil_walikelas FROM anggota_rombel ar JOIN siswa s ON s.id = ar.siswa_id WHERE ar.rombel_id = 67 AND s.status_siswa = 'Aktif';

-- 3.3 Cek 5 Siswa yang Baru Dipulihkan Beserta Nilainya
SELECT s.id, s.nis, s.nisn, s.nama_lengkap, s.nik, s.no_hp, s.kelurahan, s.kecamatan, COUNT(DISTINCT nf.id) as jml_nilai_formatif, COUNT(DISTINCT nr.id) as jml_nilai_rapor FROM siswa s LEFT JOIN nilai_formatif nf ON nf.siswa_id = s.id LEFT JOIN nilai_rapor nr ON nr.siswa_id = s.id WHERE s.id IN (165, 167, 168, 169, 170) GROUP BY s.id;

-- 3.4 Cek Apakah Masih Ada Kolom NIK/Kelurahan/Kecamatan/No HP yang Tergeser di Fairuz (Target: 0 Baris)
SELECT COUNT(*) AS sisa_kolom_tergeser_fairuz FROM siswa WHERE rombel_id = 67 AND status_siswa = 'Aktif' AND (no_hp = 'Aktif' OR kelurahan LIKE '%SD %' OR kelurahan LIKE '%SDS %' OR kelurahan LIKE '%SMP %' OR nik = nis);