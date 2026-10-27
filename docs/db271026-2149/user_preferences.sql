-- ============================================================
-- Table: `user_preferences`
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

CREATE TABLE `user_preferences` (
  `user_id` int NOT NULL,
  `notif_login` tinyint(1) DEFAULT '0',
  `two_factor` tinyint(1) DEFAULT '0',
  `bahasa` varchar(10) DEFAULT 'id',
  `theme` varchar(10) DEFAULT 'light',
  `notif_email` tinyint(1) DEFAULT '0',
  `notif_sistem` tinyint(1) DEFAULT '0',
  `notif_update` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `user_preferences` (`user_id`, `notif_login`, `two_factor`, `bahasa`, `theme`, `notif_email`, `notif_sistem`, `notif_update`) VALUES
(1, 0, 0, 'id', 'light', 0, 0, 0),
(29, 1, 0, 'id', 'light', 0, 0, 0),
(33, 0, 0, 'id', 'dark', 0, 0, 0),
(38, 0, 0, 'en', 'light', 0, 0, 0),
(61, 0, 0, 'id', 'dark', 0, 0, 0),
(63, 0, 0, 'id', 'light', 0, 0, 0),
(88, 0, 0, 'id', 'dark', 0, 0, 0),
(89, 0, 0, 'id', 'light', 0, 0, 0),
(111, 1, 0, 'id', 'dark', 1, 1, 1),
(129, 0, 0, 'id', 'light', 0, 0, 0),
(138, 0, 0, 'id', 'light', 0, 0, 0);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`);

--

SET FOREIGN_KEY_CHECKS = 1;
