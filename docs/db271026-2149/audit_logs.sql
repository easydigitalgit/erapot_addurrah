-- ============================================================
-- Table: `audit_logs`
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

CREATE TABLE `audit_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'ID Admin yang melakukan aksi',
  `action` varchar(50) NOT NULL COMMENT 'Jenis Aksi (misal: UPDATE_PERMISSION)',
  `description` text NOT NULL COMMENT 'Detail perubahan',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Alamat IP pengguna',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(3, 29, 'UPDATE_PERMISSION', 'Memperbarui matriks hak akses dinamis untuk Role ID: 3', '2404:c0:1c64:b825:91d1:5400:f168:f6ec', '2026-05-06 07:01:08');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `audit_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--

SET FOREIGN_KEY_CHECKS = 1;
