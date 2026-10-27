-- ============================================================
-- Table: `ref_juz`
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

CREATE TABLE `ref_juz` (
  `id` int NOT NULL,
  `nama_juz` varchar(50) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `mulai_surah_id` int DEFAULT NULL,
  `sampai_surah_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `ref_juz` (`id`, `nama_juz`, `keterangan`, `mulai_surah_id`, `sampai_surah_id`) VALUES
(1, 'Juz 1', 'Al-Fatihah 1 - Al-Baqarah 141', 1, 2),
(2, 'Juz 2', 'Al-Baqarah 142 - Al-Baqarah 252', 2, 2),
(3, 'Juz 3', 'Al-Baqarah 253 - Ali Imran 92', 2, 3),
(4, 'Juz 4', 'Ali Imran 93 - An-Nisa 23', 3, 4),
(5, 'Juz 5', 'An-Nisa 24 - An-Nisa 147', 4, 4),
(6, 'Juz 6', 'An-Nisa 148 - Al-Maidah 81', 4, 5),
(7, 'Juz 7', 'Al-Maidah 82 - Al-Anam 110', 5, 6),
(8, 'Juz 8', 'Al-Anam 111 - Al-Araf 87', 6, 7),
(9, 'Juz 9', 'Al-Araf 88 - Al-Anfal 40', 7, 8),
(10, 'Juz 10', 'Al-Anfal 41 - At-Taubah 92', 8, 9),
(11, 'Juz 11', 'At-Taubah 93 - Hud 5', 9, 11),
(12, 'Juz 12', 'Hud 6 - Yusuf 52', 11, 12),
(13, 'Juz 13', 'Yusuf 53 - Ibrahim 52', 12, 14),
(14, 'Juz 14', 'Al-Hijr 1 - An-Nahl 128', 15, 16),
(15, 'Juz 15', 'Al-Isra 1 - Al-Kahfi 74', 17, 18),
(16, 'Juz 16', 'Al-Kahfi 75 - Taha 135', 18, 20),
(17, 'Juz 17', 'Al-Anbiya 1 - Al-Hajj 78', 21, 22),
(18, 'Juz 18', 'Al-Muminun 1 - Al-Furqan 20', 23, 25),
(19, 'Juz 19', 'Al-Furqan 21 - An-Naml 55', 25, 27),
(20, 'Juz 20', 'An-Naml 56 - Al-Ankabut 45', 27, 29),
(21, 'Juz 21', 'Al-Ankabut 46 - Al-Ahzab 30', 29, 33),
(22, 'Juz 22', 'Al-Ahzab 31 - Yasin 27', 33, 36),
(23, 'Juz 23', 'Yasin 28 - Az-Zumar 31', 36, 39),
(24, 'Juz 24', 'Az-Zumar 32 - Fussilat 46', 39, 41),
(25, 'Juz 25', 'Fussilat 47 - Al-Jasiyah 37', 41, 45),
(26, 'Juz 26', 'Al-Ahqaf 1 - Az-Zariyat 30', 46, 51),
(27, 'Juz 27', 'Az-Zariyat 31 - Al-Hadid 29', 51, 57),
(28, 'Juz 28', 'Al-Mujadilah 1 - At-Tahrim 12', 58, 66),
(29, 'Juz 29', 'Al-Mulk 1 - Al-Mursalat 50', 67, 77),
(30, 'Juz 30', 'An-Naba 1 - An-Nas 6 (Juz Amma)', 78, 114);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `ref_juz`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `ref_juz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--

SET FOREIGN_KEY_CHECKS = 1;
