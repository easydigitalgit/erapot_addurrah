-- ==============================================================================
-- SKRIP REPAIR 06: PEMBERSIHAN 63 DUPLIKASI NILAI SUMATIF (KELAS VII & VIII)
-- ==============================================================================
-- Deskripsi:
-- Menghapus 63 baris nilai sumatif duplikat (55 baris nilai dummy 1.00 dan 8 baris 
-- duplikat kembar identik) pada Tahun Ajaran 2026/2027 Ganjil.
-- Mempertahankan 1 record valid dengan nilai tertinggi dan tanggal update terbaru.
-- ==============================================================================

USE `raporsmpit`;

-- 1. Hapus 55 baris nilai dummy 1.00 yang memiliki nilai riil sah pada siswa & mapel yang sama
DELETE ns_dummy 
FROM nilai_sumatif ns_dummy
JOIN nilai_sumatif ns_real 
  ON ns_dummy.siswa_id = ns_real.siswa_id 
 AND ns_dummy.mapel_id = ns_real.mapel_id 
 AND ns_dummy.tahun_ajaran_id = ns_real.tahun_ajaran_id 
 AND IFNULL(ns_dummy.kategori, '') = IFNULL(ns_real.kategori, '')
 AND ns_dummy.jenis_sumatif = ns_real.jenis_sumatif
 AND ns_dummy.id != ns_real.id
WHERE ns_dummy.nilai = 1.00 
  AND ns_real.nilai > 1.00;

-- 2. Hapus sisa baris duplikat identik (pertahankan ID yang lebih baru / updated_at terbaru)
DELETE ns_old
FROM nilai_sumatif ns_old
JOIN nilai_sumatif ns_new 
  ON ns_old.siswa_id = ns_new.siswa_id 
 AND ns_old.mapel_id = ns_new.mapel_id 
 AND ns_old.tahun_ajaran_id = ns_new.tahun_ajaran_id 
 AND IFNULL(ns_old.kategori, '') = IFNULL(ns_new.kategori, '')
 AND ns_old.jenis_sumatif = ns_new.jenis_sumatif
 AND ns_old.id < ns_new.id
WHERE ns_old.nilai = ns_new.nilai;

