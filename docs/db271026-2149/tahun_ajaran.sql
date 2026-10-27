-- ============================================================
-- Table: `tahun_ajaran`
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

CREATE TABLE `tahun_ajaran` (
  `id` int NOT NULL,
  `tahun` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` enum('Aktif','Arsip') DEFAULT 'Arsip',
  `tgl_mulai` date DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `tahun_ajaran` (`id`, `tahun`, `semester`, `status`, `tgl_mulai`, `tgl_akhir`, `is_locked`, `created_at`) VALUES
(5, '2024/2025', 'Ganjil', 'Arsip', NULL, NULL, 0, '2026-02-06 08:31:41'),
(6, '2024/2025', 'Genap', 'Arsip', NULL, NULL, 0, '2026-02-06 08:31:41'),
(7, '2025/2026', 'Ganjil', 'Arsip', '2025-07-01', '2025-12-31', 0, '2026-02-11 04:59:37'),
(9, '2025/2026', 'Genap', 'Arsip', '2026-01-01', '2026-06-30', 0, '2026-03-09 07:08:52'),
(10, '2026/2027', 'Ganjil', 'Aktif', '2026-07-01', '2026-12-31', 0, '2026-03-09 07:37:43'),
(12, '2026/2027', 'Genap', 'Arsip', '2026-06-01', '2027-06-01', 0, '2026-03-14 03:48:55');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--

SET FOREIGN_KEY_CHECKS = 1;
