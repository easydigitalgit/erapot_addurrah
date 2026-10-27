-- ============================================================
-- Table: `guru_tendik`
-- Split from raporsmpit.sql (per-table export)
-- Relasi/FK ada di bagian akhir file ini.
-- Catatan: file ini TIDAK men-drop tabel. Pastikan tabel kosong
-- sebelum import untuk menghindari duplikasi data.
-- ============================================================
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- Struktur tabel
--

CREATE TABLE `guru_tendik` (
  `id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `nuptk` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_pasangan` varchar(100) DEFAULT NULL,
  `jumlah_anak` int DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `status_marital` enum('Belum Menikah','Menikah','Cerai') DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `jurusan_prodi` varchar(100) DEFAULT NULL,
  `tmt_ad_durrah` date DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `suku` varchar(50) DEFAULT NULL,
  `golongan_darah` enum('A','A+','B','B+','AB','O','O+','-') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat_ktp` text,
  `status_kepegawaian` varchar(50) DEFAULT NULL,
  `jabatan_id` int DEFAULT NULL,
  `mapel_id` int DEFAULT NULL,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `no_hp` varchar(20) DEFAULT NULL,
  `no_darurat` varchar(20) DEFAULT NULL,
  `ttd_digital` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `guru_tendik` (`id`, `created_at`, `user_id`, `nuptk`, `nik`, `nama_lengkap`, `email`, `nama_pasangan`, `jumlah_anak`, `jenis_kelamin`, `status_marital`, `pendidikan_terakhir`, `jurusan_prodi`, `tmt_ad_durrah`, `tempat_lahir`, `tanggal_lahir`, `suku`, `golongan_darah`, `alamat_ktp`, `status_kepegawaian`, `jabatan_id`, `mapel_id`, `alamat_domisili`, `no_hp`, `no_darurat`, `ttd_digital`) VALUES
(1, '2026-02-05 04:38:39', 54, NULL, NULL, 'Nanda Hartono, S.Kom', 'Nandahartono8@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Sistem Informasi', '2025-07-05', 'Belawan', '1999-11-26', 'Jawa', 'A+', 'Jl. Marelan VII Gg. Indah Cat Ungu Depan Tower', 'GTY', 12, NULL, 'Jl. Marelan VII Gg. Indah Cat Ungu Depan Tower', '081265531122', '', NULL),
(2, '2026-02-05 04:38:39', 127, '1356765666130243', '1356765666130243', 'Muhammad Hamidi, S.T., M.Cs', 'hamidi.addurrah@gmail.com', 'Weli Tridayatna AS', 1, 'L', 'Menikah', 'Pascasarjana (S2)', 'Ilmu Komputer', '2020-06-30', 'Padangsidimpuan', '1987-10-24', 'Padang', 'B', 'Jl. Sekata Gg Bunga No 2 Link XVI, Kel. Sei Agul', 'GTY', 11, 7, 'Jl. Marelan Raya V Pasar II Barat Gg Baru', '085260788560', '085260788560', NULL),
(3, '2026-02-05 04:38:39', 78, NULL, '1234567', 'Ary Laksamana Putra', 'Laksamana.boy37@gmail.com', NULL, 1, 'L', 'Menikah', 'SMA/SMK/Sederajat', '-', '2022-07-04', 'Medan', '1997-10-08', 'Jawa', 'O', 'Jl suratman no 27 Marelan pasar 3 barat', NULL, 3, NULL, 'Jl suratman no 27 Marelan pasar 3 barat', '085975377987', '085975377987', 'ttd_wali_3_1779161383.webp'),
(4, '2026-02-05 04:38:39', 111, NULL, '12345', 'Septian Syahputra, A.Md. Kom', 'septiansyhputra@gmail.com', 'NURFATIMAH HUMAIROH', 1, 'L', 'Menikah', 'Diploma (D3)', 'Sistem Informasi', '2022-01-17', 'Medan', '1998-09-29', 'Jawa', 'O', 'JL. BAUT LK II JL. SERBAJADI UJUNG', NULL, 3, NULL, 'JL. BAUT LK II JL. SERBAJADI UJUNG', '082247612377', NULL, 'ttd_wali_4_1780456250.webp'),
(6, '2026-02-05 04:38:39', 129, '6737773674230072', '6737773674230072', 'Irawati Fajri Rambe, S.S, Gr', 'irawatifajri@gmail.com', 'Reza Kahfi', 2, 'P', 'Menikah', 'Sarjana (S1)', 'Sastra Indonesia', '2018-07-02', 'Sei Rampah', '1995-04-05', 'Mandailing', 'AB', 'Jl. Rawe 1 Gg. Iman Lingk. XII Kel. Tangkahan', NULL, 1, 3, 'Jl. M. Basir Lingk. 30 Gg. Ikhlas No. 222', '082161388265', '082170706176', NULL),
(7, '2026-02-05 04:38:39', 128, '5744763664230232', '5744763664230232', 'Arie Machlina, M. Hum', 'ariemachlina@gmail.com', 'Adil Arifin. MA', 6, 'P', 'Menikah', 'Pascasarjana (S2)', 'Linguistik Arab', '2007-07-09', 'Belawan', '1985-04-12', 'Padang', 'AB', 'Mesan Marelan Pasar 4 Barat', 'GTY', 1, 10, 'Pasar IV Barat, Komplek Panggon Indah, jalan Tentram 138 B\r\nMedan Marelan\r\nMedan Sumut\r\n20255', '083197719218', '087797863822', NULL),
(8, '2026-02-05 04:38:39', 136, '7748776677230192', '7748776677230192', 'Riska Handayani, S. Pd, Gr', 'Handayaniriska164@gmail.com', 'M. Hamsyahri', 1, 'P', 'Menikah', 'Sarjana (S1)', 'Pendidikan matematika', '2024-07-12', 'Medan', '1998-04-16', 'Aceh', 'O', 'Jl. A. Sani Muthalib lk. 03 pasar 2', NULL, 1, 4, 'Jl. A. Sani Muthalib pasar 4 barat', '085261458198', '085761966615', NULL),
(9, '2026-02-05 04:38:39', 122, '7860773674130272', '7860773674130272', 'Ray Agung Hadi Wiranata, SH, Gr', 'rayagungsss@gmail.com', 'Pratiwi Arinda Putri', 1, 'L', 'Menikah', 'Sarjana (S1)', 'Hukum/Ilmu Hukum', '2023-02-01', 'Medan', '1995-05-28', 'Sunda', 'O', 'JL. Kly ssudarso no.7 LK9', NULL, 3, NULL, 'Jl. Marelan I pasar IV Barat Gang Bersama', '085761728098', '082277203455', 'ttd_wali_9_1780464449.webp'),
(10, '2026-02-05 04:38:39', 140, '5842760661130292', '5842760661130292', 'Faisal Rachman, S.Sos, Gr', 'faisalrachman2121@gmail.com', 'Fitriyanti, S.Pd', 3, 'L', 'Menikah', 'Sarjana (S1)', 'Ilmu Administrasi Negara', '2019-07-08', 'Belawan', '1982-05-10', 'Minang', 'B', 'Jl. Sehat Lk. 11 Komplek Panggon Indah', NULL, 1, NULL, 'Jl. Sehat Lk. 11 Komplek Panggon Indah', '085275406201', '085270664042', NULL),
(11, '2026-02-05 04:38:39', 77, '8133773674130053', '8133773674130053', 'Surya Wijaya Purnama, M.Pd', 'swijayapurnama@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'Pascasarjana (S2)', 'Magister Tadris Bahasa Inggris', '2017-11-01', 'Tanjungbalai', '1995-08-01', 'Batak/Melayu', 'O', 'Jln.M. Abbas, Kel. Sirantau', 'GTY', 8, 6, 'Gg. Kelapa Gading, Kel. Terjun', '081534960369', '', NULL),
(12, '2026-02-05 04:38:39', 55, NULL, '1212131311111', 'Arya Abdillah', 'aryaabdillah451@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'SMA/SMK/Sederajat', 'Pendidikan Agama Islam', '2024-02-03', 'Medan', '2005-03-10', 'jawa', 'O', 'JL.ALFAKA VII NO 11 LK VI', 'Guru Tetap (GTY)', 2, 12, 'JL.ALFAKA VII NO 11 LK VI', '089604265928', NULL, NULL),
(13, '2026-02-05 04:38:39', 56, NULL, NULL, 'Teuku ravli irastu', 'teukuravli67@gmail.com', 'Masita', 0, 'L', 'Menikah', 'SMA/SMK/Sederajat', '-', '2022-10-03', 'Palembang', '1999-06-22', 'Aceh', 'B', 'Jl a sani muthalib kampung tengah lingkungan 9', 'GTY', 13, 12, 'Jl. Marelan IX lk.7 Gg.pribadi T-600 kota medan', '085180569044', '', NULL),
(14, '2026-02-05 04:38:39', 142, '2560773674130283', '2560773674130283', 'Salman Alfarizi, S. Pd, Gr', 'salmanalfarisi281296@gmail.com', NULL, 0, 'L', 'Menikah', 'Sarjana (S1)', 'Pendidikan Seni Rupa', '2023-02-03', 'Medan', '1995-12-28', 'Melayu', 'A', 'Jln. Baru gg. Saudara kel. Terjun kec. Medan marelan', NULL, 1, 8, 'Jln. Baru gg. Saudara kel. Terjun kec. Medan marelan', '081262557072', NULL, NULL),
(15, '2026-02-05 04:38:39', 147, '2542772673130133', '2542772673130133', 'Muhammad Faisal, S.Pd, Gr', 'muhammadfaisal160519@gmail.com', 'Susilawati', 1, 'L', 'Menikah', 'Sarjana (S1)', 'Bimbingan Konseling', '2021-01-06', 'Lima Laras', '1994-12-10', 'Melayu', 'A', 'JL. Marelan V Pasar 2 Barat', NULL, 10, NULL, 'JL. Marelan V Pasar 2 Barat', '085270073828', '+62 852-0698-4459', NULL),
(16, '2026-02-05 04:38:39', 58, NULL, NULL, 'Yusrina Afiqah', 'yuraqh236@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pai', '2023-01-09', 'Medan', '2006-09-05', 'Minang', 'O', 'Jl. Marelan IX lingk VII', 'GTY', 2, NULL, 'Jl. Marelan IX lingk VII', '082182920717', '', NULL),
(17, '2026-02-05 04:38:39', 146, NULL, NULL, 'Nur fauziah arrohmah', 'Ziyaahh81@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'SMA/SMK/Sederajat', 'PAI', '2023-07-10', 'Medan', '2006-01-29', 'Jawa', '-', 'Medan medan marelan', 'GTY', 13, NULL, 'Medan medan marelan', '085691302875', '', NULL),
(18, '2026-02-05 04:38:39', 59, NULL, NULL, 'Zahra firasya Daulay', 'Zdaulay65@gmail.com', 'Muhammad ghozali', 0, 'P', 'Menikah', 'SMA/SMK/Sederajat', '-', '2025-06-11', 'Medan', '2001-09-04', 'Mandailing', 'B', 'Medan Jaln durung 1 lingkungan 19 ,terjun', 'GTY', 13, NULL, 'Medan Jaln durung 1 lingkungan 19 ,terjun', '081265605094', '081265605094', NULL),
(19, '2026-02-05 04:38:39', 126, '4537774675230243', '4537774675230243', 'Damiati, S.Pd, Gr', 'damiaati12@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan Agama Islam', '2021-07-05', 'Rantauprapat', '1996-12-05', 'Jawa', 'AB', 'Kabupaten Asahan ,Kisaran Marelan', NULL, 3, 1, 'Kabupaten Asahan ,Kisaran Marelan', '081269146901', NULL, NULL),
(20, '2026-02-05 04:38:39', 145, NULL, NULL, 'Nur faridah sholehah', 'zhephin098@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'SMA/SMK/Sederajat', 'PAI', '2025-08-14', 'Medan', '2007-04-03', 'Jawa', '-', 'Marelan Medan-marelan', 'GTY', 13, NULL, 'Marelan Medan-marelan', '085805118651', '', NULL),
(21, '2026-02-05 04:38:39', 130, '1542778679130092', '1542778679130092', 'Feri Hidayat Purba, S.Pd, Gr', 'ferihidayatpurba@gmail.com', 'Atika J.H. Nst, S.Pd.', 0, 'L', 'Menikah', 'Sarjana (S1)', 'B. Indonesia', '2022-07-02', 'Pakam', '2000-02-10', 'Batak Simalungun', '-', 'Rokan Hilir, Riau Jl. Paku, Gg. Siku', NULL, 1, 3, 'Rokan Hilir, Riau Jl. Paku, Gg. Siku', '085668943044', '+62 822-7280-2150', NULL),
(22, '2026-02-05 04:38:39', 148, '8049776677230043', '8049776677230043', 'Widya Astika Nasution, S.Pd, Gr', 'Widyaastikanasution@gmail.com', 'M.Rizky Azis Azhari, S.Kom', 1, 'P', 'Menikah', 'Sarjana (S1)', 'Bimbingan dan konseling', '2021-07-05', 'Medan', '1998-07-17', 'Mandailing', 'A+', 'Jln. Marelan I Psr. IV barat Gg. Mulia, Link. 7', NULL, 10, NULL, 'Jln. Marelan I Psr. IV barat Gg. Mulia, Link. 7', '081264764026', '082272854778', NULL),
(23, '2026-02-05 04:38:39', 114, '9747774675230072', '9747774675230072', 'Selly Apriya, S.Pd. Gr', 'sellyapriya@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan Matematika', '2019-07-06', 'Medan', '1996-04-15', 'Jawa', 'O', 'Jl. Marelan 3 Lk. 14', NULL, 3, 4, 'Jl. Marelan 3 Lk. 14', '0813-9773-3580', NULL, 'ttd_wali_23_1780545869.webp'),
(24, '2026-02-05 04:38:39', 119, '2161776677230043', '2161776677230043', 'Yuli  Aisyah, S.Pd, Gr', 'yuliaisyah29@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Matematika', '2021-01-11', 'Medan', '1998-10-03', 'Jawa', 'O', 'Jl Paku Ujung, Sekoci Tanah 600', NULL, 7, 4, 'Jl Paku Ujung, Sekoci Tanah 600', '085373180096', NULL, NULL),
(25, '2026-02-05 04:38:39', 118, '7546763664230192', '7546763664230192', 'Nina Fitriani, S. Pd, Gr', 'Uminina0@gmail.com', 'Riski Hamdani', 1, 'P', 'Menikah', 'Sarjana (S1)', 'B. Indonesia', '2018-07-02', 'Belawan', '1985-02-14', 'Melayu', 'O', 'Jalan Young Panah Hijau Gang Teratai', NULL, 9, 3, 'Jalan Young Panah Hijau Gang Teratai', '081276102324', '082191078894', NULL),
(26, '2026-02-05 04:38:39', 135, '4637778679230062', '4637778679230062', 'Hannia Luthfi, S. Pd, Gr', 'Hannialuthfi1@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan Matematika', '2023-07-17', 'Medan', '2006-03-05', 'Jawa', 'B', 'Jln. Paku Gg. Bahagia No.12', NULL, 1, 4, 'Jln. Paku Gg. Bahagia No.12', '081396151242', NULL, NULL),
(27, '2026-02-05 04:48:36', 89, '5035759661110073', '5035759661110073', 'Muhammad Taufiq, S.Pd, Gr', 'muhammad.taufiq6145@admin.smp.belajar.id', 'Dwi Wulandari, S.Pd', 4, 'L', 'Menikah', 'Sarjana (S1)', 'Pendidikan Ekonomi / Tata Niaga', '2025-07-14', 'Medan', '1981-07-03', 'Mandailing', 'O', 'Jl. Tuasan No 85 A Kel Sidorejo Hilir', 'Guru Tetap (GTY)', 6, 7, 'Jl. Tuasan No 85 A Kel Sidorejo Hilir', '081264187387', '082276421470', 'ttd_kepsek_1781743316.webp'),
(28, '2026-02-05 04:48:36', 133, '1455774675230222', '1455774675230222', 'Amalia Ulfa, S.Si, Gr', 'Amaliaulfa21@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Fisika', '2022-07-15', 'Medan', '1996-01-23', 'Melayu', 'A', 'Jl. Ileng Lor. Kuini Rengas Pulau', NULL, 1, NULL, 'Jl. Ileng Lor. Kuini Rengas Pulau', '082370628536', NULL, NULL),
(29, '2026-02-05 04:48:36', NULL, NULL, NULL, 'Dr. Syamsul Hadi, M.Pd.I', 'syamsuluika@gmail.com', 'Aswita Nazli, S.Pd.I', 4, 'L', 'Menikah', 'Doktor (S3)', 'Pendidikan Islam', '2019-11-01', 'Nganjuk', '1976-05-27', 'Jawa', 'B', 'Pasar 4 Timur Gang Tower Lingkungan 28', 'GTY', 1, NULL, 'Lingkungan 28 Kelurahan Rengas Pulau', '081263312950', '', NULL),
(30, '2026-02-05 04:48:36', 120, '9962772673230142', '9962772673230142', 'Anggana Ayu Marina, S.Pd, Gr', 'angganaayu.marina30@gmail.com', 'Ade Rahmad Kurnia NST', 0, 'P', 'Menikah', 'Sarjana (S1)', 'Pendidikan Luar Sekolah', '2018-07-02', 'Kotapinang', '1994-06-30', 'Mandailing', 'A', 'Komp. panggon Indah Link 11 Medan Marelan', NULL, 1, NULL, 'Komp. Panggon Indah Link 11 Medan Marelan', '082385515440', '081375324959', NULL),
(31, '2026-02-05 04:48:36', 138, '5149778679230083', '5149778679230083', 'Dina Alya Salsabil, S. Pd, Gr', 'dinaalya333@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan Jasmani', '2023-07-08', 'Medan', '2000-08-17', 'Jawa', 'O', 'Jl Pancing I Gg Mesjid no. 4', NULL, 1, NULL, 'Jl Pancing I Gg Mesjid no. 4', '088261460105', NULL, NULL),
(32, '2026-02-05 04:48:36', NULL, NULL, NULL, '', 'b8460848@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'SMA/SMK/Sederajat', 'PAI', '2023-07-03', 'Labuhan Deli', '2004-10-05', 'Jawa', 'A', 'Pasar 4 Barat komplek panggon indah No.115', 'GTY', 2, NULL, 'Dusun XV desa karang gading kec.labuhan Deli', '083865262666', '', NULL),
(33, '2026-02-05 04:48:36', 137, '6258766668300013', '6258766668300013', 'Rita Sugianto, S.Pd.I, Gr', 'rita.sugianto26091988@gmail.com', 'Indra Purnomo, S.Pd.I', 4, 'P', 'Menikah', 'Sarjana (S1)', 'PAI', '2020-06-01', 'Medan', '1988-09-26', 'Jawa', 'B', 'Jl. Mesjid GG. Kariono psr. 5 Marelan', NULL, 1, NULL, 'Jl. Mesjid GG. Kariono psr 5 Marelan', '085372985459', '085227022936', NULL),
(34, '2026-02-05 04:48:36', 112, NULL, '12346701233', 'Muhammad Fahrozi Sianipar', 'fakhrozimohammed@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan agama Islam', '2021-10-03', 'Labuhan Deli', '2000-05-31', 'Batak', 'B', 'Jln young panah Hijau GG wakaf', NULL, 3, NULL, 'Jln young panah Hijau GG wakaf', NULL, NULL, 'ttd_wali_34_1780359807.webp'),
(35, '2026-02-05 04:48:36', 132, '8150774675230133', '8150774675230133', 'Vinari Muzdalifah, S.Pd, Gr', 'vinarimuzdalifah2@gmail.com', 'Lando Abdul Azis Lubis', 2, 'P', 'Menikah', 'Sarjana (S1)', 'Pendidikan Fisika', '2021-07-05', 'Marihat Ulu', '1996-08-18', 'Jawa', 'O', 'Jalan Marelan I pasar 4 barat', NULL, 1, NULL, 'Jalan Platina 4 lingkungan X gang Waker', '082276454649', '082276100202', NULL),
(36, '2026-02-05 04:48:36', 124, '2347774675230173', '2347774675230173', 'Suri Khairunnisa, S.Si, Gr', 'Surikhairunnisa2@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'FISIKA', '2022-07-15', 'Medan', '1996-10-15', 'Melayu', 'B', 'Jln. Bom lama link 25 Medan Labuhan', NULL, 3, 5, 'Link 25 Pekan Labuhan', '082275689659', '085276047917', 'ttd_wali_36_1780450907.webp'),
(37, '2026-02-05 04:48:36', NULL, '2952776677130192', '2952776677130192', 'Muhammad Amri', 'amrimuhammad2098@gmail.com', 'Riska Handayani (Istri)', 1, 'L', 'Menikah', 'Sarjana (S1)', 'Pendidikan Agama Islam', '2021-01-18', 'Jakarta', '1998-06-20', 'Jawa', 'O', 'Jl. Abdul Sani Muthalib Lingkungan 03 Pasar 02', NULL, 1, NULL, 'Jl. Abdul Sani Muthalib Lingkungan 03 Pasar 02', '085761966615', '085261458198', NULL),
(38, '2026-02-05 04:48:36', 116, '7438771672230153', '7438771672230153', 'Eli Saprida, S. Pd,Gr', 'sapridaeli21@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan Bahasa Inggris', '2019-07-06', 'Medan', '1993-11-06', 'Jawa', 'A', 'Jalan Marelan lingkungan 04 Pasar 3', NULL, 3, 6, 'Lingkungan 04 Pasar 3 Kelurahan Terjun', '081374070990', NULL, 'ttd_wali_38_1792710323.webp'),
(39, '2026-02-05 04:48:36', 139, '1533757658230183', '1533757658230183', 'Endang Purwaningsih, S. IP, Gr', 'endang242555@gmail.com', 'Ibrahim', 3, 'P', 'Menikah', 'Sarjana (S1)', 'Ilmu Pemerintahan', '2020-07-06', 'Medan', '1979-12-01', 'Jawa', 'B', 'Jl. P. Usman Umar Desa Lama, Hamparan Perak', NULL, 1, NULL, 'Jl. Jala IX ling. IV Paya Pasir Medan Marelan', '082162136413', '085207755761', NULL),
(41, '2026-02-05 04:48:36', 63, '8261779680130023', '8261779680130023', 'Adhitya Prayoga, S.Pd, Gr', 'adhityaprayoga29@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'Sarjana (S1)', 'Pendidikan IPA', '2023-07-08', 'Medan', '2001-09-29', 'Jawa', 'O', 'Jl. Bejo gg. Longsleep II, Benteng Hilir', NULL, 3, NULL, 'Jl. Mansyurdin gg. Anggrek Merah III', '081269454659', NULL, 'ttd_wali_41_1780454763.webp'),
(42, '2026-02-05 04:48:36', 131, '8343773674230233', '8343773674230233', 'Nanda Nauri ,S.Pd', 'nandanauri1110@gmail.com', 'Khairul Rosyadi Daulay', 1, 'P', 'Menikah', 'Sarjana (S1)', 'Pendidikan Bahasa Inggris', '2022-07-15', 'Belawan', '1995-10-11', 'Jawa', 'B', 'Jl.Perunggu Gg.Amal Lingk VI Kec.Medan Deli', 'GTY', 1, 6, 'Jl.Perunggu Gg.Amal Lingk VI Kec.Medan Deli', '085270743606', '081376380469', NULL),
(43, '2026-02-05 04:48:36', 121, '2947770671230232', '2947770671230232', 'DAHRATUL AINI, S.S, Gr', 'dahratulaini1212@gmail.com', 'DEDI', 1, 'P', 'Menikah', 'Sarjana (S1)', 'SASTRA INGGRIS', '2020-07-08', 'Banda Aceh', '1992-06-15', 'ACEH', 'B', 'PERUMAHAN BUMI MIKRAJ 2 BLOK A 31', NULL, 3, NULL, 'JL.KARYA LK XI NO.149 A', '085359619421', NULL, NULL),
(44, '2026-02-05 04:48:36', 123, '4544770671130023', '4544770671130023', 'Tika Yeardila, M.Pd', 'yeardilatika12@gmail.com', 'Muhammad Rasyid', 1, 'P', 'Menikah', 'Pascasarjana (S2)', 'Pendidikan Bahasa Inggris', '2022-07-15', 'Sipotu', '1992-12-12', 'Minang', 'O', 'Marelan, Ps.3 Barat gg. Mono', NULL, 1, 6, NULL, '085364993312', '081260709242', 'ttd_wali_44_1781748310.webp'),
(45, '2026-02-05 04:48:36', 134, '4844760661300142', '4844760661300142', 'Dian Andriani Gultom, S.Pd, Gr', 'diangultom43@gmail.com', 'Heri Rustiawan', 4, 'P', 'Menikah', 'Sarjana (S1)', 'Ekonomi', '2019-07-06', 'Medan', '1982-05-12', 'Batak', 'A', 'Marelan, pasar4 medan Jl. Sejahtera, no 21', NULL, 1, 14, 'Komplek Panggon Indah', '0823-6336-0428', '0822-8147-5056', NULL),
(46, '2026-02-05 04:48:36', 61, '3837768669130592', '3837768669130592', 'Muhammad Al Aufa, S.Kom, Gr', 'muhammadalaufa@gmail.com', 'Juwita Agustrisna', 2, 'L', 'Menikah', 'Sarjana (S1)', 'Sistem Informasi', '2023-06-01', 'Medan', '1990-05-05', 'Banjar', 'B', 'Jl. Kapten Muslim Gg. Setia No. 1', NULL, 5, NULL, 'Jl. Bambu III No. 10', '085296875149', NULL, NULL),
(47, '2026-02-05 04:48:36', 144, NULL, NULL, 'Muhammad Thoriq', 'thoriqmuhammad869@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'SMA/SMK/Sederajat', 'Ilmu agama', '2025-07-15', 'Medan', '2005-01-14', 'Melayu', 'O', 'Lorong alfalah lingkungan 30 Pekan labuhan', 'GTY', 2, NULL, 'Lorong alfalah lingkungan 30 Pekan labuhan', '082163869480', '', NULL),
(48, '2026-02-05 04:48:36', 151, '232342325363633', '23435323464363634', 'Fatimah Hasibuan, S.Pd', 'fatimahhasibuan245@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'SMA/SMK/Sederajat', 'Tahfiz', '2022-01-17', 'Hasahatan Julu', '2001-12-14', 'Batak', '-', 'Medan marelan komplek panggon indah Pasar 4 barat', NULL, 1, 12, 'Hasahatan Julu', '081211066020', '081376003793', NULL),
(49, '2026-02-05 04:48:36', 143, NULL, NULL, 'FAUZIE RAMADHAN, S.Pd', 'oziie.nns@gmail.com', NULL, 0, 'L', 'Belum Menikah', 'Sarjana (S1)', 'PENDIDIKAN AGAMA ISLAM', '2023-07-20', 'MEDAN', '2001-11-25', 'PADANG', 'O', 'JL MARELAN IX LINGK VII', 'GTY', 2, NULL, 'JL MARELAN IX LINGK VII', '082382421313', '', NULL),
(50, '2026-02-05 04:48:36', 125, NULL, NULL, 'Yasir Fauzan S.Pd.I', 'yasirfauzan2211r@gmail.com', 'Rabiah', 3, 'L', 'Menikah', 'Sarjana (S1)', 'PAI', '2022-10-18', 'Huraba', '1993-11-22', 'Mandailing', 'B', 'Komplek Marelan indah pasar 3 barat', 'GTY', 3, NULL, 'Jl Kamboja raya no 02 prumnas Helvetia', '085262884424', '082272202278', 'ttd_wali_50_1779157796.webp'),
(51, '2026-02-05 04:54:25', 30, NULL, '232342342342', 'Luthfia Chairunnisa, S.Pd', 'luthfiachairunnisa135@gmail.com', NULL, 0, 'P', 'Belum Menikah', 'SMA/SMK/Sederajat', '-', '2022-07-16', 'Medan', '2003-08-07', 'Minang/Mandailing', 'B', 'Jl. Marelan IX Link VII', NULL, 3, NULL, 'Jl. Marelan IX Link VII', '081361834395', '081361701042', 'ttd_wali_51_1780530203.webp'),
(86, '2026-03-27 22:48:32', 79, '34324234', '23423423', 'Salsa Octapiani', 'SalsaOctapiani@gmail.com', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Honorer', 13, 12, NULL, '85261939513', NULL, NULL),
(87, '2026-03-27 22:52:50', 87, '213123234234', '234212', 'Zulfaka', 'Zulfaka@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Tetap (GTY)', 2, 12, NULL, '212134321', NULL, NULL),
(111, '2026-03-30 07:16:20', 115, NULL, 'AUTO-55471', 'M. Hamsyahri, S.Pd, Gr', 'hamsyahri123@gmail.com', NULL, NULL, 'L', NULL, NULL, 'Pendidikan agama Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, NULL, 'ttd_wali_111_1780461621.webp'),
(115, '2026-03-30 07:16:21', 141, NULL, 'AUTO-76405', 'Khairana Zarifah Siregar, S. Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, '2026-04-23 08:01:46', 152, '485686686584568585', '57556574545848', 'Tuty Alawiyah Daulay, S.Pd', 'alawiyahdaulay67@gmail.com', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL),
(137, '2026-04-23 08:06:03', 153, '588568685854765', '546475675467654868', 'Alfi Ramadhan', 'alfiramadhan817@gmail.com', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL),
(138, '2026-04-23 08:25:28', 154, NULL, '95695664574647', 'Rizky Ramadhani S.Pd', 'rizkyramadhani551@gmail.com', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, NULL, NULL, NULL, NULL),
(139, '2026-04-23 08:28:00', 155, NULL, '46557658675995675', 'Cut Tania Ivana', 'Ivanatania79@gmail.com', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 12, NULL, NULL, NULL, NULL),
(140, '2026-04-23 08:29:53', 156, NULL, '3453657458458658', 'Siti Rohimah', 'rohimaharray1108@gmail.com', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL),
(141, '2026-04-23 08:32:50', 157, '9940768669130312', '5747564848679679', 'T.M. Reza Fahlevi, S.Pd., Gr.', 'rezha.alfarruq@gmail.com', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL),
(143, '2026-10-08 00:35:48', 226, NULL, '1271135709030001', 'Diny Patika Sari, S.Pd.', 'dinypatikasari@school.sch.id', NULL, NULL, 'P', 'Belum Menikah', 'S1', 'Pendidikan Seni Rupa', NULL, NULL, '2003-09-17', 'Melayu', 'B', 'link 6 pekan labuhan', 'Guru Tetap (GTY)', 1, 8, 'link 6 pekan labuhan', '081215289376', '081375522521', NULL),
(144, '2026-10-08 00:53:18', 227, NULL, '1271126111020003', 'HARIS ALFARISI SIREGAR, S.Pd', 'harissrg21@gmail.com', NULL, NULL, 'L', 'Belum Menikah', 'S1', 'Pendidikan Kepelatihan Olahraga', NULL, 'MEDAN', '2002-11-21', 'BATAK MANDAILING', 'O', 'Jl. baut Gg amal no 10 link II MEDAN MERELAN', 'Guru Tetap (GTY)', 1, 9, 'Jl. baut Gg amal no 10 link II MEDAN MERELAN', '082179164960', '085274355420', NULL);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `guru_tendik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `nip` (`nuptk`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `guru_tendik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--

-- Relasi / Foreign Key
--
ALTER TABLE `guru_tendik`
  ADD CONSTRAINT `guru_tendik_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--

SET FOREIGN_KEY_CHECKS = 1;
