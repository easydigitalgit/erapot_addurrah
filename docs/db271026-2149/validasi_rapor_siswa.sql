-- ============================================================
-- Table: `validasi_rapor_siswa`
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

CREATE TABLE `validasi_rapor_siswa` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `is_locked` tinyint(1) DEFAULT '1',
  `locked_at` datetime DEFAULT NULL,
  `locked_by` int DEFAULT NULL,
  `tahun_ajaran_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Index
--
ALTER TABLE `validasi_rapor_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gurumapel_tahunajaran_baru` (`tahun_ajaran_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `validasi_rapor_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
