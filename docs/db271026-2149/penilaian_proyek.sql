-- ============================================================
-- Table: `penilaian_proyek`
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

CREATE TABLE `penilaian_proyek` (
  `id` int NOT NULL,
  `guru_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `rombel_id` int NOT NULL,
  `nama_proyek` varchar(255) NOT NULL,
  `jenis` enum('Individu','Kelompok') NOT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `kkm` int NOT NULL DEFAULT '75',
  `deskripsi` text,
  `tahun_ajaran_id` int NOT NULL,
  `status` varchar(20) DEFAULT 'Draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `penilaian_proyek` (`id`, `guru_id`, `mapel_id`, `rombel_id`, `nama_proyek`, `jenis`, `tanggal_pelaksanaan`, `kkm`, `deskripsi`, `tahun_ajaran_id`, `status`, `created_at`) VALUES
(1, 29, 3, 8, 'OSN Matematika', 'Kelompok', '2026-03-03', 98, 'Khusus orang pintar', 9, 'Draft', '2026-03-03 13:56:35'),
(2, 41, 10, 12, 'asd', 'Individu', '2026-03-11', 75, '-', 9, 'Draft', '2026-03-28 11:39:53'),
(3, 41, 10, 12, 'popo', 'Kelompok', '2026-03-06', 75, 'pknoiion', 9, 'Draft', '2026-03-28 11:41:15');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `penilaian_proyek`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `penilaian_proyek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--

SET FOREIGN_KEY_CHECKS = 1;
