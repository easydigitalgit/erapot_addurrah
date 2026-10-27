-- ============================================================
-- Table: `master_jabatan`
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

CREATE TABLE `master_jabatan` (
  `id` int NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `master_jabatan` (`id`, `nama_jabatan`) VALUES
(1, 'Guru Mapel'),
(2, 'Pembina Tahfiz'),
(3, 'Wali Kelas'),
(4, 'Tendik'),
(5, 'Kepala Lab TIK'),
(6, 'Kepala Sekolah'),
(7, 'Wakil Kepsek'),
(8, 'Waka Kurikulum'),
(9, 'Waka Kesiswaan'),
(10, 'Guru BK'),
(11, 'Ka. TU / Operator'),
(12, 'Staff Tata Usaha'),
(13, 'Guru Tahfidz');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `master_jabatan`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `master_jabatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--

SET FOREIGN_KEY_CHECKS = 1;
