-- ============================================================
-- Table: `rombel`
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

CREATE TABLE `rombel` (
  `id` int NOT NULL,
  `nama_rombel` varchar(20) NOT NULL,
  `tingkat` enum('VII','VIII','IX') NOT NULL,
  `kurikulum_id` int DEFAULT NULL,
  `id_tahun_ajaran` int DEFAULT NULL,
  `wali_kelas_id` int DEFAULT NULL,
  `semester` enum('Ganjil','Genap') DEFAULT NULL,
  `is_lulus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `rombel` (`id`, `nama_rombel`, `tingkat`, `kurikulum_id`, `id_tahun_ajaran`, `wali_kelas_id`, `semester`, `is_lulus`) VALUES
(1, 'Amazonite', 'IX', 1, 9, 3, 'Ganjil', 1),
(7, 'Zamrud', 'IX', 1, 9, 50, 'Ganjil', 1),
(8, 'Opal', 'IX', 1, 9, 34, 'Ganjil', 1),
(9, 'Ruby', 'IX', 1, 9, 19, 'Ganjil', 1),
(10, 'Carnelian', 'IX', 1, 9, 38, 'Ganjil', 1),
(11, 'Fairuz', 'VIII', 1, 9, 111, 'Ganjil', 0),
(12, 'Emerald', 'VIII', 1, 9, 9, 'Genap', 0),
(13, 'Biduri', 'VIII', 1, 9, 44, 'Ganjil', 0),
(14, 'Crystal', 'VIII', 1, 9, 36, 'Ganjil', 0),
(15, 'Diamond', 'VIII', 1, 9, 51, 'Ganjil', 0),
(16, 'Granit', 'VII', 1, 9, 4, 'Ganjil', 0),
(17, 'Topaz', 'VII', 1, 9, 41, 'Ganjil', 0),
(18, 'Intan', 'VII', 1, 9, 30, 'Ganjil', 0),
(19, 'Kyanite', 'VII', 1, 9, 23, 'Ganjil', 0),
(20, 'Safir', 'VII', 1, 9, 43, 'Ganjil', 0),
(67, 'Fairuz', 'IX', 1, 10, 111, 'Ganjil', 0),
(68, 'Emerald', 'IX', 1, 10, 9, 'Ganjil', 0),
(69, 'Biduri', 'IX', 1, 10, 30, 'Ganjil', 0),
(70, 'Crystal', 'IX', 1, 10, 23, 'Ganjil', 0),
(71, 'Diamond', 'IX', 1, 10, 43, 'Ganjil', 0),
(72, 'Granit', 'VIII', 1, 10, 34, 'Ganjil', 0),
(73, 'Topaz', 'VIII', 1, 10, 50, 'Ganjil', 0),
(74, 'Intan', 'VIII', 1, 10, 38, 'Ganjil', 0),
(75, 'Kyanite', 'VIII', 1, 10, 36, 'Ganjil', 0),
(76, 'Safir', 'VIII', 1, 10, 51, 'Ganjil', 0),
(77, 'Amazonite', 'VII', 1, 10, 41, 'Ganjil', 0),
(78, 'Opal', 'VII', 1, 10, 3, 'Ganjil', 0),
(79, 'Zamrud', 'VII', 1, 10, 4, 'Ganjil', 0),
(80, 'Carnelian', 'VII', 1, 10, 44, 'Ganjil', 0),
(81, 'Ruby', 'VII', 1, 10, 19, 'Ganjil', 0);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `rombel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rombel_ibfk_1` (`wali_kelas_id`),
  ADD KEY `fk_rombel_kurikulum` (`kurikulum_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `rombel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--

SET FOREIGN_KEY_CHECKS = 1;
