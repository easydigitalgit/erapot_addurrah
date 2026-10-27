-- ============================================================
-- Table: `password_resets`
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

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(17, 'dev@gmail.com', '2ce570286dfb95a9199e2d685b50b296c860715d4510a86141d6b0589bba1429', '2026-03-10 19:17:15'),
(18, 'mhafizhardansyah@gmail.com', '9f5b6e0b1be69786fc261306b58afdf756b41d5a182b909af5cca535a11088ef', '2026-03-10 19:20:43'),
(20, 'fatimahhasibuan245@gmail.com', '7c2ee8d3a46255e53e6040063933632365ca633a95f96d59f0a650a0df0d3bb9', '2026-04-24 17:11:29');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--

SET FOREIGN_KEY_CHECKS = 1;
