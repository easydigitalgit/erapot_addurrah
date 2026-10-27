-- =====================================================================
-- MIGRATION SCRIPT 08: CONSTRAINTS, SOFT DELETE, AND AUDIT TRAIL SKEMA
-- Aplikasi: e-Rapor SMP IT Ad Durrah
-- Eksekusi: 28 Oktober 2026
-- =====================================================================

-- 1. Bersihkan 2 duplikat Master LM usang (ID 124, 125)
DELETE FROM `master_lm` WHERE `id` IN (124, 125);

-- 2. Tambah kolom soft delete (deleted_at) pada 6 tabel kunci
ALTER TABLE `siswa` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `foto_siswa`;
ALTER TABLE `anggota_rombel` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `created_at`;
ALTER TABLE `master_lm` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `nilai_formatif` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `nilai_sumatif` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `nilai_rapor` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;

-- 3. Pasang UNIQUE KEY Constraints anti-duplikasi fisik
ALTER TABLE `siswa` ADD UNIQUE KEY `uq_siswa_nis` (`nis`);
ALTER TABLE `master_lm` ADD UNIQUE KEY `uq_master_lm_unique` (`mapel_id`, `tingkat`, `semester`, `kategori`, `kode_lm`);
ALTER TABLE `nilai_sumatif` ADD UNIQUE KEY `uq_sumatif_siswa_mapel_ta_kat_jenis` (`siswa_id`, `mapel_id`, `tahun_ajaran_id`, `kategori`, `jenis_sumatif`);
ALTER TABLE `nilai_formatif` ADD UNIQUE KEY `uq_formatif_lengkap` (`siswa_id`, `mapel_id`, `rombel_id`, `tahun_ajaran_id`, `semester`, `kategori`, `jenis_penilaian`, `pertemuan`);

-- 4. Perbarui skema audit_logs untuk merekam detail aktor dan data before/after
ALTER TABLE `audit_logs` 
  ADD COLUMN `role` VARCHAR(50) NULL AFTER `user_id`,
  ADD COLUMN `tabel` VARCHAR(50) NULL AFTER `role`,
  ADD COLUMN `record_id` INT NULL AFTER `tabel`,
  ADD COLUMN `old_data` LONGTEXT NULL AFTER `description`,
  ADD COLUMN `new_data` LONGTEXT NULL AFTER `old_data`,
  ADD COLUMN `user_agent` TEXT NULL AFTER `ip_address`;

