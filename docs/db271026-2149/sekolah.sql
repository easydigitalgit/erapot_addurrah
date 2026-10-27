-- ============================================================
-- Table: `sekolah`
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

CREATE TABLE `sekolah` (
  `id` int NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL,
  `npsn` varchar(20) NOT NULL,
  `nss` varchar(30) DEFAULT NULL,
  `jenjang` enum('SDIT','SMPIT','SMAIT') DEFAULT 'SMPIT',
  `status_sekolah` enum('Negeri','Swasta') DEFAULT 'Swasta',
  `tahun_berdiri` year DEFAULT NULL,
  `akreditasi` enum('A','B','C','Belum') DEFAULT 'Belum',
  `alamat` text,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `desa_id` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'default_logo.png',
  `warna_primary` varchar(7) DEFAULT '#1F7A4D',
  `warna_secondary` varchar(7) DEFAULT '#E6F4EC',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `sekolah` (`id`, `nama_sekolah`, `npsn`, `nss`, `jenjang`, `status_sekolah`, `tahun_berdiri`, `akreditasi`, `alamat`, `kecamatan`, `kabupaten`, `provinsi`, `desa_id`, `kode_pos`, `telepon`, `email`, `website`, `logo`, `warna_primary`, `warna_secondary`, `created_at`, `updated_at`) VALUES
(1, 'SMP Swasta  IT Ad Durrah', '69969823', '', 'SMPIT', 'Swasta', '2017', 'A', 'Jl. Selamat II No.7', '12.71.12', '12.71', '12', '24801', '20255', '081370055707', 'smpitaddurrah@gmail.com', 'https://smpitaddurrah.com', '1775102489_9ddabc174c8bc7282db6.png', '#a78301', '#f4f3e6', '2026-02-06 23:24:11', '2026-04-27 08:42:17');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--

SET FOREIGN_KEY_CHECKS = 1;
