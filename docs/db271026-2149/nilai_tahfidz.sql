-- ============================================================
-- Table: `nilai_tahfidz`
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

CREATE TABLE `nilai_tahfidz` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `tahun_ajaran_id` int NOT NULL COMMENT 'ID dari tabel tahun_ajaran',
  `semester` enum('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  `juz_id` int DEFAULT NULL,
  `predikat` varchar(50) NOT NULL COMMENT 'Misal: Sangat Baik / A',
  `nilai_teori` int DEFAULT '0',
  `deskripsi` text NOT NULL COMMENT 'Catatan narasi untuk di rapor',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

-- Data
--

INSERT INTO `nilai_tahfidz` (`id`, `siswa_id`, `tahun_ajaran_id`, `semester`, `juz_id`, `predikat`, `nilai_teori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 191, 9, 'Genap', 30, 'Jayyid Jiddan', 80, 'Capaian hafalan ananda sudah baik dengan predikat Jayyid Jiddan (Lancar) dan rata-rata nilai 83. Ananda telah menyelesaikan hafalan pada Surah An-Naziat di Juz 30. Hasil evaluasi teori tajwid meraih predikat Jayyid Jiddan (80). Tingkatkan terus muroja\'ah ananda.', '2026-05-05 09:42:42', '2026-05-06 09:28:26'),
(2, 192, 9, 'Genap', 30, 'Mutqin', 0, 'Alhamdulillah, capaian hafalan ananda sangat membanggakan dengan predikat Mutqin (Sangat Lancar) dan rata-rata nilai 90. Ananda telah menyelesaikan hafalan pada Surah An-Naziat di Juz 30. Teruslah berinteraksi dengan Al-Quran.', '2026-05-06 09:25:46', '2026-05-06 09:28:26');

-- --------------------------------------------------------

--

-- Index
--
ALTER TABLE `nilai_tahfidz`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nilai_juz_baru` (`siswa_id`,`tahun_ajaran_id`,`semester`,`juz_id`),
  ADD KEY `fk_nilai_juz_baru` (`juz_id`);

--

-- AUTO_INCREMENT
--
ALTER TABLE `nilai_tahfidz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--

-- Relasi / Foreign Key
--
ALTER TABLE `nilai_tahfidz`
  ADD CONSTRAINT `fk_nilai_juz_baru` FOREIGN KEY (`juz_id`) REFERENCES `ref_juz` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--

SET FOREIGN_KEY_CHECKS = 1;
