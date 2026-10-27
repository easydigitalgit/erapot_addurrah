-- ============================================================
-- Table: `notifikasi`
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

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','success','warning','error') DEFAULT 'info',
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `link`, `is_read`, `created_at`) VALUES
(1, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260409_021907.sql', 'success', 'admin/backup', 0, '2026-04-09 02:19:07'),
(2, 33, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260410_023223.sql', 'success', 'admin/backup', 0, '2026-04-10 02:32:23'),
(3, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260411_020903.sql', 'success', 'admin/backup', 0, '2026-04-11 02:09:03'),
(4, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260412_035617.sql', 'success', 'admin/backup', 0, '2026-04-12 03:56:17'),
(5, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260413_020738.sql', 'success', 'admin/backup', 0, '2026-04-13 02:07:38'),
(6, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260415_080305.sql', 'success', 'admin/backup', 0, '2026-04-15 08:03:05'),
(7, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260416_030351.sql', 'success', 'admin/backup', 0, '2026-04-16 03:03:51'),
(8, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260417_020623.sql', 'success', 'admin/backup', 0, '2026-04-17 02:06:23'),
(9, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260418_135449.sql', 'success', 'admin/backup', 0, '2026-04-18 13:54:49'),
(10, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260422_034402.sql', 'success', 'admin/backup', 0, '2026-04-22 03:44:02'),
(11, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260423_020845.sql', 'success', 'admin/backup', 0, '2026-04-23 02:08:46'),
(12, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260424_025738.sql', 'success', 'admin/backup', 0, '2026-04-24 02:57:38'),
(13, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260425_020044.sql', 'success', 'admin/backup', 0, '2026-04-25 02:00:44'),
(14, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260426_122723.sql', 'success', 'admin/backup', 0, '2026-04-26 12:27:23'),
(15, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260427_065709.sql', 'success', 'admin/backup', 0, '2026-04-27 06:57:09'),
(16, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260428_035305.sql', 'success', 'admin/backup', 0, '2026-04-28 03:53:05'),
(17, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260429_041251.sql', 'success', 'admin/backup', 0, '2026-04-29 04:12:51'),
(18, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260430_112532.sql', 'success', 'admin/backup', 0, '2026-04-30 11:25:32'),
(19, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260502_175829.sql', 'success', 'admin/backup', 0, '2026-05-02 17:58:29'),
(20, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260503_102807.sql', 'success', 'admin/backup', 0, '2026-05-03 10:28:07'),
(21, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260505_021258.sql', 'success', 'admin/backup', 0, '2026-05-05 02:12:58'),
(22, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260507_061344.sql', 'success', 'admin/backup', 0, '2026-05-07 06:13:44'),
(23, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260508_020458.sql', 'success', 'admin/backup', 0, '2026-05-08 02:04:58'),
(24, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260512_053716.sql', 'success', 'admin/backup', 0, '2026-05-12 05:37:16'),
(25, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260513_141926.sql', 'success', 'admin/backup', 0, '2026-05-13 14:19:26'),
(26, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260514_021756.sql', 'success', 'admin/backup', 0, '2026-05-14 02:17:56'),
(27, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260517_071809.sql', 'success', 'admin/backup', 0, '2026-05-17 07:18:09'),
(28, 89, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260518_035040.sql', 'success', 'admin/backup', 0, '2026-05-18 03:50:40'),
(29, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260519_032947.sql', 'success', 'admin/backup', 0, '2026-05-19 03:29:47'),
(30, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260520_065948.sql', 'success', 'admin/backup', 0, '2026-05-20 06:59:48'),
(31, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260521_031816.sql', 'success', 'admin/backup', 0, '2026-05-21 03:18:16'),
(32, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260529_145511.sql', 'success', 'admin/backup', 0, '2026-05-29 14:55:11'),
(33, 33, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260530_141205.sql', 'success', 'admin/backup', 0, '2026-05-30 14:12:06'),
(34, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260531_160129.sql', 'success', 'admin/backup', 0, '2026-05-31 16:01:29'),
(35, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260602_135929.sql', 'success', 'admin/backup', 0, '2026-06-02 13:59:29'),
(36, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260603_051054.sql', 'success', 'admin/backup', 0, '2026-06-03 05:10:54'),
(37, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260604_030034.sql', 'success', 'admin/backup', 0, '2026-06-04 03:00:34'),
(38, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260605_020007.sql', 'success', 'admin/backup', 0, '2026-06-05 02:00:07'),
(39, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260606_032530.sql', 'success', 'admin/backup', 0, '2026-06-06 03:25:30'),
(40, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260615_050035.sql', 'success', 'admin/backup', 0, '2026-06-15 05:00:35'),
(41, 111, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260616_025130.sql', 'success', 'admin/backup', 0, '2026-06-16 02:51:30'),
(42, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260617_021803.sql', 'success', 'admin/backup', 0, '2026-06-17 02:18:03'),
(43, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260807_112813.sql', 'success', 'admin/backup', 0, '2026-08-07 11:28:13'),
(44, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260903_031724.sql', 'success', 'admin/backup', 0, '2026-09-03 03:17:24'),
(45, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260904_035018.sql', 'success', 'admin/backup', 0, '2026-09-04 03:50:19'),
(46, 78, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260916_044123.sql', 'success', 'admin/backup', 0, '2026-09-16 04:41:23'),
(47, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260924_152952.sql', 'success', 'admin/backup', 0, '2026-09-24 15:29:52'),
(48, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20260929_093017.sql', 'success', 'admin/backup', 0, '2026-09-29 09:30:17'),
(49, 111, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261005_150138.sql', 'success', 'admin/backup', 0, '2026-10-05 15:01:39'),
(50, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261006_032734.sql', 'success', 'admin/backup', 0, '2026-10-06 03:27:34'),
(51, 63, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261007_025201.sql', 'success', 'admin/backup', 0, '2026-10-07 02:52:01'),
(52, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261008_021022.sql', 'success', 'admin/backup', 0, '2026-10-08 02:10:22'),
(53, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261009_044133.sql', 'success', 'admin/backup', 0, '2026-10-09 04:41:33'),
(54, 63, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261011_044643.sql', 'success', 'admin/backup', 0, '2026-10-11 04:46:43'),
(55, 123, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261012_030432.sql', 'success', 'admin/backup', 0, '2026-10-12 03:04:32'),
(56, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261013_024006.sql', 'success', 'admin/backup', 0, '2026-10-13 02:40:06'),
(57, 123, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261014_020646.sql', 'success', 'admin/backup', 0, '2026-10-14 02:06:46'),
(58, 123, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261015_042302.sql', 'success', 'admin/backup', 0, '2026-10-15 04:23:03'),
(59, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261016_120339.sql', 'success', 'admin/backup', 0, '2026-10-16 12:03:39'),
(60, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261019_041854.sql', 'success', 'admin/backup', 0, '2026-10-19 04:18:54'),
(61, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261020_021049.sql', 'success', 'admin/backup', 0, '2026-10-20 02:10:49'),
(62, 29, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261021_020312.sql', 'success', 'admin/backup', 0, '2026-10-21 02:03:12'),
(63, 123, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261022_030149.sql', 'success', 'admin/backup', 0, '2026-10-22 03:01:49'),
(64, 123, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261023_020128.sql', 'success', 'admin/backup', 0, '2026-10-23 02:01:28'),
(65, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261024_020440.sql', 'success', 'admin/backup', 0, '2026-10-24 02:04:40'),
(66, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261025_062241.sql', 'success', 'admin/backup', 0, '2026-10-25 06:22:41'),
(67, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261026_032730.sql', 'success', 'admin/backup', 0, '2026-10-26 03:27:30'),
(68, 77, 'Auto Backup Berhasil', 'Sistem telah mencadangkan database secara otomatis: Auto_Full_20261027_025620.sql', 'success', 'admin/backup', 0, '2026-10-27 02:56:21');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--

SET FOREIGN_KEY_CHECKS = 1;
