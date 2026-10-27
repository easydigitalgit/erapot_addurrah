-- ============================================================
-- Table: `paket_soal`
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

CREATE TABLE `paket_soal` (
  `id` int NOT NULL,
  `guru_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `tingkat` varchar(10) NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `kelas_target` varchar(255) NOT NULL COMMENT 'Contoh: Kelas 8A, Kelas 8B',
  `kumpulan_soal_id` text NOT NULL COMMENT 'Menyimpan array ID soal dari bank_soal',
  `status` varchar(20) DEFAULT 'Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `paket_soal`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `paket_soal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--

SET FOREIGN_KEY_CHECKS = 1;
