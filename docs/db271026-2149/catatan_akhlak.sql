-- ============================================================
-- Table: `catatan_akhlak`
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

CREATE TABLE `catatan_akhlak` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `rombel_id` int NOT NULL,
  `kategori_akhlak` varchar(255) DEFAULT NULL,
  `status_pembinaan` varchar(50) DEFAULT NULL,
  `tindak_lanjut` text,
  `catatan` text NOT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `catatan_akhlak`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `catatan_akhlak`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
