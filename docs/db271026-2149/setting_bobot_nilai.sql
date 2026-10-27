-- ============================================================
-- Table: `setting_bobot_nilai`
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

CREATE TABLE `setting_bobot_nilai` (
  `id` int NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `sub_kategori` varchar(50) NOT NULL,
  `bobot` int NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `setting_bobot_nilai` (`id`, `kategori`, `sub_kategori`, `bobot`, `updated_at`) VALUES
(46, 'tengah_semester', 'nh', 35, '2026-04-07 10:22:13'),
(47, 'tengah_semester', 'uh', 35, '2026-04-07 10:22:13'),
(48, 'tengah_semester', 'sts', 30, '2026-04-07 10:22:13'),
(49, 'akhir_semester', 'nh', 35, '2026-04-07 10:22:13'),
(50, 'akhir_semester', 'uh', 35, '2026-04-07 10:22:13'),
(51, 'akhir_semester', 'sts', 15, '2026-04-07 10:22:13'),
(52, 'akhir_semester', 'sas', 15, '2026-04-07 10:22:13');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `setting_bobot_nilai`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `setting_bobot_nilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--

SET FOREIGN_KEY_CHECKS = 1;
