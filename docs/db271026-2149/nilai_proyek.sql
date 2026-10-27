-- ============================================================
-- Table: `nilai_proyek`
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

CREATE TABLE `nilai_proyek` (
  `id` int NOT NULL,
  `proyek_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `nilai_json` text NOT NULL COMMENT 'Menyimpan nilai per aspek rubrik (JSON)',
  `nilai_akhir` int NOT NULL DEFAULT '0',
  `catatan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `nilai_proyek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proyek_siswa_unik` (`proyek_id`,`siswa_id`),
  ADD KEY `fk_nilai_siswa` (`siswa_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `nilai_proyek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

-- Relasi / Foreign Key
--
ALTER TABLE `nilai_proyek`
  ADD CONSTRAINT `fk_nilai_proyek` FOREIGN KEY (`proyek_id`) REFERENCES `penilaian_proyek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_nilai_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

--

SET FOREIGN_KEY_CHECKS = 1;
