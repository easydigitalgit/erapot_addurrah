-- ============================================================
-- Table: `role_permissions`
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

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `role_id` int NOT NULL COMMENT 'ID dari tabel roles',
  `module_name` varchar(50) NOT NULL COMMENT 'Nama modul (contoh: pengguna, akademik)',
  `can_view` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Boleh, 0 = Ditolak',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_update` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `can_special` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Akses khusus seperti cetak/lock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `role_permissions` (`id`, `role_id`, `module_name`, `can_view`, `can_create`, `can_update`, `can_delete`, `can_special`) VALUES
(6, 2, 'guru_dashboard', 1, 1, 1, 1, 0),
(7, 2, 'guru_kelas', 1, 1, 1, 1, 0),
(8, 2, 'guru_penilaian', 1, 1, 1, 1, 0),
(9, 2, 'guru_sikap', 1, 1, 1, 1, 0),
(10, 2, 'guru_materi', 1, 1, 1, 1, 0),
(12, 3, 'wali_dashboard', 0, 0, 0, 0, 0),
(13, 3, 'wali_kelas', 1, 1, 1, 1, 0),
(14, 3, 'wali_monitoring', 1, 1, 1, 0, 0),
(15, 3, 'wali_karakter', 0, 0, 0, 0, 0),
(16, 3, 'wali_rapor', 1, 0, 1, 0, 0);

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_module_unique` (`role_id`,`module_name`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--

SET FOREIGN_KEY_CHECKS = 1;
