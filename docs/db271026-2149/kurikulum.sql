-- ============================================================
-- Table: `kurikulum`
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

CREATE TABLE `kurikulum` (
  `id` int NOT NULL,
  `nama_kurikulum` varchar(100) NOT NULL,
  `jenis` enum('Merdeka','K13','Lainnya') NOT NULL,
  `tahun_berlaku` year DEFAULT NULL,
  `status` enum('Aktif','Non-aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `kurikulum` (`id`, `nama_kurikulum`, `jenis`, `tahun_berlaku`, `status`) VALUES
(1, 'Kurikulum Merdeka', 'Merdeka', '2024', 'Aktif'),
(2, 'Kurikulum 2013', 'K13', '2013', 'Non-aktif');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `kurikulum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--

SET FOREIGN_KEY_CHECKS = 1;
