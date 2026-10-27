-- ============================================================
-- Table: `master_ekskul`
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

CREATE TABLE `master_ekskul` (
  `id` int NOT NULL,
  `nama_ekskul` varchar(100) NOT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `master_ekskul` (`id`, `nama_ekskul`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pramuka', 'Aktif', '2026-03-26 11:01:20', '2026-03-26 11:01:20'),
(2, 'PMR (Palang Merah Remaja)', 'Aktif', '2026-03-26 11:01:20', '2026-03-26 11:01:20'),
(3, 'Futsal', 'Aktif', '2026-03-26 11:01:20', '2026-03-26 11:01:20'),
(4, 'Paskibra', 'Aktif', '2026-03-26 11:01:20', '2026-03-26 11:01:20'),
(5, 'Hadroh', 'Aktif', '2026-03-27 04:51:14', '2026-03-27 04:51:14'),
(6, 'Olimpiade IPS', 'Aktif', '2026-03-27 04:51:39', '2026-03-27 04:51:39'),
(7, 'Taekwondo', 'Aktif', '2026-03-27 04:51:58', '2026-03-27 04:51:58'),
(8, 'Renang', 'Aktif', '2026-03-27 05:05:40', '2026-03-27 05:05:40'),
(9, 'Tarung Drajat', 'Aktif', '2026-03-27 06:38:00', '2026-03-27 06:38:00'),
(10, 'Silat', 'Aktif', '2026-03-27 06:38:19', '2026-03-27 06:38:19'),
(11, 'Kaligrafi', 'Aktif', '2026-03-27 06:38:39', '2026-03-27 06:38:39'),
(12, 'Karya Ilmiah Remaja', 'Aktif', '2026-03-27 06:38:52', '2026-03-27 06:38:52'),
(13, 'Olimpiade Matematika', 'Aktif', '2026-03-27 06:39:11', '2026-03-27 06:39:11'),
(14, 'Arabic Club', 'Aktif', '2026-03-27 06:39:25', '2026-03-27 06:39:25'),
(15, 'Olimpiade IPA', 'Aktif', '2026-03-27 06:39:34', '2026-03-27 06:39:34'),
(16, 'Mujawwad Qur`an', 'Aktif', '2026-03-27 06:39:56', '2026-03-27 06:39:56'),
(17, 'English Club', 'Aktif', '2026-03-27 06:40:05', '2026-03-27 06:40:05'),
(18, 'Desain Grafis', 'Aktif', '2026-03-27 06:40:54', '2026-03-27 06:40:54'),
(19, 'Panahan', 'Aktif', '2026-03-27 06:41:07', '2026-03-27 06:41:07');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `master_ekskul`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `master_ekskul`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--

SET FOREIGN_KEY_CHECKS = 1;
