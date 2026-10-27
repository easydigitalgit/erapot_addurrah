-- ============================================================
-- Table: `ref_surah`
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

CREATE TABLE `ref_surah` (
  `id` int NOT NULL,
  `no_surah` int NOT NULL,
  `nama_surah` varchar(100) NOT NULL,
  `nama_surah_arab` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `arti_surah` varchar(100) DEFAULT NULL,
  `jumlah_ayat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `ref_surah` (`id`, `no_surah`, `nama_surah`, `nama_surah_arab`, `arti_surah`, `jumlah_ayat`) VALUES
(1, 1, 'Al-Fatihah', 'الفاتحة', 'Pembukaan', 7),
(2, 2, 'Al-Baqarah', 'البقرة', 'Sapi Betina', 286),
(3, 3, 'Ali Imran', 'آل عمران', 'Keluarga Imran', 200),
(4, 4, 'An-Nisa', 'النساء', 'Wanita', 176),
(5, 5, 'Al-Maidah', 'المائدة', 'Jamuan (Hidangan Makanan)', 120),
(6, 6, 'Al-Anam', 'الأنعام', 'Binatang Ternak', 165),
(7, 7, 'Al-Araf', 'الأعراف', 'Tempat yang Tertinggi', 206),
(8, 8, 'Al-Anfal', 'الأنفال', 'Harta Rampasan Perang', 75),
(9, 9, 'At-Taubah', 'التوبة', 'Pengampunan', 129),
(10, 10, 'Yunus', 'يونس', 'Nabi Yunus', 109),
(11, 11, 'Hud', 'هود', 'Nabi Hud', 123),
(12, 12, 'Yusuf', 'يوسف', 'Nabi Yusuf', 111),
(13, 13, 'Ar-Rad', 'الرعد', 'Guruh (Petir)', 43),
(14, 14, 'Ibrahim', 'إبراهيم', 'Nabi Ibrahim', 52),
(15, 15, 'Al-Hijr', 'الحجر', 'Al Hijr (Nama Gunung)', 99),
(16, 16, 'An-Nahl', 'النحل', 'Lebah', 128),
(17, 17, 'Al-Isra', 'الإسراء', 'Memperjalankan di Malam Hari', 111),
(18, 18, 'Al-Kahfi', 'الكهف', 'Penghuni-penghuni Gua', 110),
(19, 19, 'Maryam', 'مريم', 'Maryam (Maria)', 98),
(20, 20, 'Taha', 'طه', 'Ta Ha', 135),
(21, 21, 'Al-Anbiya', 'الأنبياء', 'Nabi-Nabi', 112),
(22, 22, 'Al-Hajj', 'الحج', 'Haji', 78),
(23, 23, 'Al-Muminun', 'المؤمنون', 'Orang-orang Mukmin', 118),
(24, 24, 'An-Nur', 'النور', 'Cahaya', 64),
(25, 25, 'Al-Furqan', 'الفرقان', 'Pembeda', 77),
(26, 26, 'Asy-Syuara', 'الشعراء', 'Penyair', 227),
(27, 27, 'An-Naml', 'النمل', 'Semut', 93),
(28, 28, 'Al-Qasas', 'القصص', 'Cerita', 88),
(29, 29, 'Al-Ankabut', 'العنكبوت', 'Laba-laba', 69),
(30, 30, 'Ar-Rum', 'الروم', 'Bangsa Romawi', 60),
(31, 31, 'Luqman', 'لقمان', 'Keluarga Luqman', 34),
(32, 32, 'As-Sajdah', 'السجدة', 'Sajdah', 30),
(33, 33, 'Al-Ahzab', 'الأحزاب', 'Golongan-Golongan yang Bersekutu', 73),
(34, 34, 'Saba', 'سبأ', 'Kaum Saba', 54),
(35, 35, 'Fatir', 'فاطر', 'Pencipta', 45),
(36, 36, 'Yasin', 'يس', 'Yaasiin', 83),
(37, 37, 'As-Saffat', 'الصافات', 'Barisan-barisan', 182),
(38, 38, 'Sad', 'ص', 'Shaad', 88),
(39, 39, 'Az-Zumar', 'الزمر', 'Rombongan-rombongan', 75),
(40, 40, 'Ghafir', 'غافر', 'Yang Mengampuni', 85),
(41, 41, 'Fussilat', 'فصلت', 'Yang Dijelaskan', 54),
(42, 42, 'Asy-Syura', 'الشورى', 'Musyawarah', 53),
(43, 43, 'Az-Zukhruf', 'الزخرف', 'Perhiasan', 89),
(44, 44, 'Ad-Dukhan', 'الدخان', 'Kabut', 59),
(45, 45, 'Al-Jasiyah', 'الجاثية', 'Yang Berlutut', 37),
(46, 46, 'Al-Ahqaf', 'الأحقاف', 'Bukit-bukit Pasir', 35),
(47, 47, 'Muhammad', 'محمد', 'Nabi Muhammad', 38),
(48, 48, 'Al-Fath', 'الفتح', 'Kemenangan', 29),
(49, 49, 'Al-Hujurat', 'الحجرات', 'Kamar-kamar', 18),
(50, 50, 'Qaf', 'ق', 'Qaaf', 45),
(51, 51, 'Az-Zariyat', 'الذاريات', 'Angin yang Menerbangkan', 60),
(52, 52, 'At-Tur', 'الطور', 'Bukit', 49),
(53, 53, 'An-Najm', 'النجم', 'Bintang', 62),
(54, 54, 'Al-Qamar', 'القمر', 'Bulan', 55),
(55, 55, 'Ar-Rahman', 'الرحمن', 'Yang Maha Pemurah', 78),
(56, 56, 'Al-Waqiah', 'الواقعة', 'Hari Kiamat', 96),
(57, 57, 'Al-Hadid', 'الحديد', 'Besi', 29),
(58, 58, 'Al-Mujadilah', 'المجادلة', 'Wanita yang Mengajukan Gugatan', 22),
(59, 59, 'Al-Hasyr', 'الحشر', 'Pengusiran', 24),
(60, 60, 'Al-Mumtahanah', 'الممتحنة', 'Wanita yang Diuji', 13),
(61, 61, 'As-Saff', 'الصف', 'Satu Barisan', 14),
(62, 62, 'Al-Jumuah', 'الجمعة', 'Hari Jum’at', 11),
(63, 63, 'Al-Munafiqun', 'المنافقون', 'Orang-orang yang Munafik', 11),
(64, 64, 'At-Taghabun', 'التغابن', 'Hari Dinampakkan Kesalahan-kesalahan', 18),
(65, 65, 'At-Talaq', 'الطلاق', 'Talak', 12),
(66, 66, 'At-Tahrim', 'التحريم', 'Mengharamkan', 12),
(67, 67, 'Al-Mulk', 'الملك', 'Kerajaan', 30),
(68, 68, 'Al-Qalam', 'القلم', 'Pena', 52),
(69, 69, 'Al-Haqqah', 'الحاقة', 'Hari Kiamat', 52),
(70, 70, 'Al-Maarij', 'المعارج', 'Tempat Naik', 44),
(71, 71, 'Nuh', 'نوح', 'Nabi Nuh', 28),
(72, 72, 'Al-Jinn', 'الجن', 'Jin', 28),
(73, 73, 'Al-Muzzammil', 'المزمل', 'Orang yang Berselimut', 20),
(74, 74, 'Al-Muddassir', 'المدثر', 'Orang yang Berkemul', 56),
(75, 75, 'Al-Qiyamah', 'القيامة', 'Kiamat', 40),
(76, 76, 'Al-Insan', 'الإنسان', 'Manusia', 31),
(77, 77, 'Al-Mursalat', 'المرسلات', 'Malaikat Yang Diutus', 50),
(78, 78, 'An-Naba', 'النبأ', 'Berita Besar', 40),
(79, 79, 'An-Naziat', 'النازعات', 'Malaikat-Malaikat Yang Mencabut', 46),
(80, 80, 'Abasa', 'عبس', 'Ia Bermuka Masam', 42),
(81, 81, 'At-Takwir', 'التكوير', 'Menggulung', 29),
(82, 82, 'Al-Infitar', 'الانفطار', 'Terbelah', 19),
(83, 83, 'Al-Mutaffifin', 'المطففين', 'Orang-orang yang Curang', 36),
(84, 84, 'Al-Inshiqaq', 'الانشقاق', 'Terbelah', 25),
(85, 85, 'Al-Buruj', 'البروج', 'Gugusan Bintang', 22),
(86, 86, 'At-Tariq', 'الطارق', 'Yang Datang di Malam Hari', 17),
(87, 87, 'Al-Ala', 'الأعلى', 'Yang Paling Tinggi', 19),
(88, 88, 'Al-Ghasyiyah', 'الغاشية', 'Hari Pembalasan', 26),
(89, 89, 'Al-Fajr', 'الفجر', 'Fajar', 30),
(90, 90, 'Al-Balad', 'البلد', 'Negeri', 20),
(91, 91, 'Asy-Syams', 'الشمس', 'Matahari', 15),
(92, 92, 'Al-Lail', 'الليل', 'Malam', 21),
(93, 93, 'Ad-Duha', 'الضحى', 'Waktu Matahari Sepenggalahan Naik (Dhuha)', 11),
(94, 94, 'Al-Insyirah', 'الشرح', 'Melapangkan', 8),
(95, 95, 'At-Tin', 'التين', 'Buah Tin', 8),
(96, 96, 'Al-Alaq', 'العلق', 'Segumpal Darah', 19),
(97, 97, 'Al-Qadr', 'القدر', 'Kemuliaan', 5),
(98, 98, 'Al-Bayyinah', 'البينة', 'Pembuktian', 8),
(99, 99, 'Az-Zalzalah', 'الزلزلة', 'Kegoncangan', 8),
(100, 100, 'Al-Adiyat', 'العاديات', 'Berlari Kencang', 11),
(101, 101, 'Al-Qariah', 'القارعة', 'Hari Kiamat', 11),
(102, 102, 'At-Takasur', 'التكاثر', 'Bermegah-megahan', 8),
(103, 103, 'Al-Asr', 'العصر', 'Masa', 3),
(104, 104, 'Al-Humazah', 'الهمزة', 'Pengumpat', 9),
(105, 105, 'Al-Fil', 'الفيل', 'Gajah', 5),
(106, 106, 'Quraisy', 'قريش', 'Suku Quraisy', 4),
(107, 107, 'Al-Maun', 'الماعون', 'Barang-barang yang Berguna', 7),
(108, 108, 'Al-Kautsar', 'الكوثر', 'Nikmat yang Berlimpah', 3),
(109, 109, 'Al-Kafirun', 'الكافرون', 'Orang-orang Kafir', 6),
(110, 110, 'An-Nasr', 'النصر', 'Pertolongan', 3),
(111, 111, 'Al-Lahab', 'المسد', 'Gejolak Api', 5),
(112, 112, 'Al-Ikhlas', 'الإخلاص', 'Ikhlas', 4),
(113, 113, 'Al-Falaq', 'الفلق', 'Waktu Subuh', 5),
(114, 114, 'An-Nas', 'الناس', 'Manusia', 6);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `ref_surah`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `ref_surah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--

SET FOREIGN_KEY_CHECKS = 1;
