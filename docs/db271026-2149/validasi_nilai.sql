-- ============================================================
-- Table: `validasi_nilai`
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

CREATE TABLE `validasi_nilai` (
  `id` int UNSIGNED NOT NULL,
  `rombel_id` int UNSIGNED NOT NULL,
  `progress_akademik` decimal(5,2) DEFAULT '0.00',
  `progress_karakter` decimal(5,2) DEFAULT '0.00',
  `progress_tahfidz` decimal(5,2) DEFAULT '0.00',
  `is_locked` tinyint(1) DEFAULT '0',
  `locked_at` datetime DEFAULT NULL,
  `locked_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `validasi_nilai` (`id`, `rombel_id`, `progress_akademik`, `progress_karakter`, `progress_tahfidz`, `is_locked`, `locked_at`, `locked_by`, `created_at`, `updated_at`) VALUES
(1, 16, 100.00, 0.00, 0.00, 0, NULL, NULL, '2026-05-05 09:58:35', '2026-05-05 10:00:25');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `validasi_nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_rombel` (`rombel_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `validasi_nilai`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--

SET FOREIGN_KEY_CHECKS = 1;
