-- ==========================================================================
-- STEP 2: PEMBERSIHAN 142 DUPLIKASI ANGGOTA ROMBEL & PENCEGAHAN
-- Menghapus baris duplikat (siswa_id, rombel_id) yang berulang di anggota_rombel
-- Dan menambahkan UNIQUE INDEX agar duplikasi tidak terjadi lagi di masa depan
-- Tanggal: 27 Oktober 2026
-- ==========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Hapus 142 baris duplikat, mempertahankan baris dengan ID paling awal (MIN id)
DELETE ar1 
FROM `anggota_rombel` ar1
INNER JOIN `anggota_rombel` ar2 
WHERE ar1.id > ar2.id 
  AND ar1.rombel_id = ar2.rombel_id 
  AND ar1.siswa_id = ar2.siswa_id;

-- 2. Pastikan tabel siswa memiliki rombel_id yang sinkron dengan anggota_rombel
UPDATE `siswa` s
INNER JOIN `anggota_rombel` ar ON s.id = ar.siswa_id
INNER JOIN `rombel` r ON ar.rombel_id = r.id
SET s.rombel_id = ar.rombel_id
WHERE r.id_tahun_ajaran = (SELECT id FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1)
  AND (s.rombel_id IS NULL OR s.rombel_id != ar.rombel_id);

-- 3. Tambahkan Unique Index untuk mencegah duplikasi di masa depan (jika belum ada)
-- ALTER TABLE `anggota_rombel` ADD UNIQUE KEY `uq_siswa_rombel` (`siswa_id`, `rombel_id`);

SET FOREIGN_KEY_CHECKS = 1;

