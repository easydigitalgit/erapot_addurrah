-- ============================================================
-- Table: `backup_settings`
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

CREATE TABLE `backup_settings` (
  `id` int NOT NULL,
  `auto_backup` tinyint(1) DEFAULT '1',
  `frequency` varchar(20) DEFAULT 'daily',
  `execution_time` time DEFAULT '02:00:00',
  `retention_days` int DEFAULT '30',
  `notify_email` tinyint(1) DEFAULT '1',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `backup_settings` (`id`, `auto_backup`, `frequency`, `execution_time`, `retention_days`, `notify_email`, `updated_at`) VALUES
(1, 1, 'daily', '02:00:00', 30, 1, '2026-03-06 10:24:52');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `backup_settings`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `backup_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--

SET FOREIGN_KEY_CHECKS = 1;
