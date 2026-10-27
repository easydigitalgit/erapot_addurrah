-- ============================================================
-- Table: `roles`
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

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'Super Admin', NULL),
(2, 'Guru', NULL),
(3, 'Wali Kelas', NULL),
(4, 'Siswa', NULL),
(5, 'Orang Tua', NULL),
(6, 'Guru Tahfidzh', NULL);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--

SET FOREIGN_KEY_CHECKS = 1;
