-- ============================================================
-- Table: `mata_pelajaran`
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

CREATE TABLE `mata_pelajaran` (
  `id` int NOT NULL,
  `nomor_urut` int NOT NULL DEFAULT '0',
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kkm` int NOT NULL DEFAULT '75',
  `kelompok` varchar(50) DEFAULT NULL,
  `jp_minggu` int DEFAULT NULL,
  `kurikulum_id` int DEFAULT NULL,
  `status` enum('Aktif','Non-aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `mata_pelajaran` (`id`, `nomor_urut`, `kode_mapel`, `nama_mapel`, `kkm`, `kelompok`, `jp_minggu`, `kurikulum_id`, `status`) VALUES
(1, 1, 'PAIBP', 'Pendidikan Agama Islam dan Budi Pekerti', 75, 'Keislaman', 3, 2, 'Aktif'),
(2, 2, 'PPKN', 'Pendidikan Pancasila', 75, 'Umum', 2, 2, 'Aktif'),
(3, 3, 'B.IND', 'Bahasa Indonesia', 75, 'Umum', 6, 2, 'Aktif'),
(4, 4, 'MTK', 'Matematika', 75, 'Umum', 5, 2, 'Aktif'),
(5, 5, 'IPA', 'Ilmu Pengetahuan Alam', 75, 'Umum', 5, 2, 'Aktif'),
(6, 7, 'BING', 'Bahasa Inggris', 75, 'Umum', 5, 2, 'Aktif'),
(7, 8, 'IT', 'Informatika', 75, 'Umum', 3, 2, 'Aktif'),
(8, 9, 'SB', 'Seni Budaya', 75, 'Umum', 3, 2, 'Aktif'),
(9, 10, 'PJOK', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 75, 'Umum', 3, 2, 'Aktif'),
(10, 11, 'BARA', 'Bahasa Arab', 75, 'Keislaman', 2, 2, 'Aktif'),
(11, 13, 'HDS', 'Hadist', 75, 'Keislaman', 2, 2, 'Aktif'),
(12, 0, 'Tahfidz', 'Tahfizh', 75, 'Keislaman', 2, 2, 'Aktif'),
(14, 6, 'IPS', 'Ilmu Pengetahuan Sosial', 75, 'Umum', 4, 2, 'Aktif'),
(43, 0, 'BPI-00', 'BPI', 75, 'Keislaman', 2, 2, 'Aktif'),
(44, 12, 'NW', 'Nahwu', 75, 'Keislaman', 1, 2, 'Aktif');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`),
  ADD KEY `kurikulum_id` (`kurikulum_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--

SET FOREIGN_KEY_CHECKS = 1;
