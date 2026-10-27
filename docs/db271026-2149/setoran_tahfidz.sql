-- ============================================================
-- Table: `setoran_tahfidz`
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

CREATE TABLE `setoran_tahfidz` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `guru_id` int NOT NULL COMMENT 'ID Guru penyimak',
  `tanggal` date NOT NULL,
  `jenis_setoran` enum('Ziyadah','Murojaah') NOT NULL DEFAULT 'Ziyadah',
  `juz_id` int DEFAULT NULL,
  `surah_id` int DEFAULT NULL,
  `surah` varchar(100) NOT NULL COMMENT 'Nama Surah',
  `ayat` varchar(50) NOT NULL COMMENT 'Rentang Ayat',
  `predikat` enum('Sangat Lancar','Lancar','Kurang Lancar','Belum Hafal') NOT NULL DEFAULT 'Lancar',
  `nilai` int DEFAULT '0',
  `catatan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nilai_hfl` int DEFAULT '0',
  `nilai_hrf` int DEFAULT '0',
  `nilai_m` int DEFAULT '0',
  `nilai_t` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `setoran_tahfidz` (`id`, `siswa_id`, `guru_id`, `tanggal`, `jenis_setoran`, `juz_id`, `surah_id`, `surah`, `ayat`, `predikat`, `nilai`, `catatan`, `created_at`, `updated_at`, `nilai_hfl`, `nilai_hrf`, `nilai_m`, `nilai_t`) VALUES
(1, 191, 48, '2026-05-05', 'Ziyadah', 30, 79, 'An-Naziat', 'Semua', 'Lancar', 80, NULL, '2026-05-05 09:38:21', '2026-05-05 09:46:11', 80, 80, 80, 80),
(2, 191, 48, '2026-05-04', 'Ziyadah', 30, 78, 'An-Naba', 'Semua', 'Lancar', 85, NULL, '2026-05-05 09:41:32', '2026-05-05 09:41:32', 0, 0, 0, 0),
(4, 191, 48, '2026-05-06', 'Ziyadah', 29, 67, 'Al-Mulk', 'Semua', 'Lancar', 86, NULL, '2026-05-06 03:57:36', '2026-05-06 03:57:36', 0, 0, 0, 0);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `setoran_tahfidz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_setoran_juz_baru` (`juz_id`),
  ADD KEY `fk_setoran_surah_baru` (`surah_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `setoran_tahfidz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--

SET FOREIGN_KEY_CHECKS = 1;
