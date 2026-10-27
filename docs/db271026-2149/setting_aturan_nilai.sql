-- ============================================================
-- Table: `setting_aturan_nilai`
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

CREATE TABLE `setting_aturan_nilai` (
  `id` int NOT NULL,
  `predikat` varchar(5) NOT NULL,
  `deskripsi_predikat` varchar(100) NOT NULL,
  `nilai_min` int NOT NULL,
  `nilai_max` int NOT NULL,
  `deskripsi_kompetensi` text,
  `warna_badge` varchar(20) DEFAULT 'emerald',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `setting_aturan_nilai` (`id`, `predikat`, `deskripsi_predikat`, `nilai_min`, `nilai_max`, `deskripsi_kompetensi`, `warna_badge`, `is_active`) VALUES
(5, 'D', 'Sangat Kurang', 0, 74, 'Siswa masih sangat kurang memahami materi', 'red', 1),
(6, 'C', 'Kurang', 66, 84, 'Siswa masih kurang memahami materi', 'amber', 1),
(7, 'B', 'Baik', 75, 89, 'Siswa sudah mampu memahami materi dengan baik', 'blue', 1),
(8, 'A', 'Sangat Baik', 90, 99, 'Siswa sudah memahami materi dengan sangat baik', 'emerald', 1);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `setting_aturan_nilai`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `setting_aturan_nilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--

SET FOREIGN_KEY_CHECKS = 1;
