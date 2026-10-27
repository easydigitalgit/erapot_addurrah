-- ============================================================
-- Table: `nilai_komponen`
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

CREATE TABLE `nilai_komponen` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `rombel_id` int NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `harian` int DEFAULT '0',
  `uts` int DEFAULT '0',
  `uas` int DEFAULT '0',
  `proyek` int DEFAULT '0',
  `catatan` text,
  `aspek_kedisiplinan` tinyint(1) DEFAULT '0',
  `aspek_tanggung_jawab` tinyint(1) DEFAULT '0',
  `aspek_kerjasama` tinyint(1) DEFAULT '0',
  `aspek_kejujuran` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `nilai_komponen`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `nilai_komponen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
