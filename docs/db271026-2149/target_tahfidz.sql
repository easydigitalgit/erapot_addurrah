-- ============================================================
-- Table: `target_tahfidz`
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

CREATE TABLE `target_tahfidz` (
  `id` int NOT NULL,
  `tingkat` enum('VII','VIII','IX') NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `juz_id` int NOT NULL,
  `surah_mulai_id` int DEFAULT NULL,
  `surah_sampai_id` int DEFAULT NULL,
  `minimal_hafalan` int DEFAULT '0',
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `target_tahfidz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_juz` (`juz_id`),
  ADD KEY `idx_surah_mulai` (`surah_mulai_id`),
  ADD KEY `idx_surah_sampai` (`surah_sampai_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `target_tahfidz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
