-- ============================================================
-- Table: `observasi_sikap`
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

CREATE TABLE `observasi_sikap` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `rombel_id` int NOT NULL,
  `parameter_sikap` varchar(50) NOT NULL,
  `skala` varchar(50) NOT NULL,
  `catatan` text NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `observasi_sikap`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `observasi_sikap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
