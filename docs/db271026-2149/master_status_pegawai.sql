-- ============================================================
-- Table: `master_status_pegawai`
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

CREATE TABLE `master_status_pegawai` (
  `id` int NOT NULL,
  `nama_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `master_status_pegawai` (`id`, `nama_status`) VALUES
(1, 'Guru Tetap (GTY)'),
(2, 'Guru Honorer'),
(3, 'PNS / ASN');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `master_status_pegawai`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `master_status_pegawai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--

SET FOREIGN_KEY_CHECKS = 1;
