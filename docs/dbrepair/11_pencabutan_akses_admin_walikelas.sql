-- ==============================================================================
-- SKRIP REPAIR 11: PENCABUTAN HAK AKSES ADMIN DARI SEMUA WALI KELAS
-- ==============================================================================
-- Aplikasi  : eRapor SMP IT Addurrah
-- Database  : raporsmpit
-- Tanggal   : 28 Oktober 2026
-- Deskripsi : Mencabut hak akses Super Admin (role_id = 1) dari seluruh akun guru
--             yang bertugas sebagai Wali Kelas di tabel `rombel`.
--             Mengembalikan role default di tabel `users` menjadi Guru (role_id = 2)
--             dan menghapus entri Super Admin di tabel multi-role `user_roles`.
-- ==============================================================================

USE `raporsmpit`;

-- ------------------------------------------------------------------------------
-- 1. PRATINJAU DATA WALI KELAS DENGAN AKSES ADMIN SEBELUM DIUBAH
-- ------------------------------------------------------------------------------
SELECT 
    u.id AS user_id, 
    u.username, 
    gt.nama_lengkap AS nama_guru, 
    u.role_id AS default_role_id,
    CONCAT(r.tingkat, ' ', r.nama_rombel) AS rombel_diampu,
    ta.tahun AS tahun_ajaran,
    ur.id AS user_roles_id,
    ur.role_id AS multi_role_id
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.role_id = 1
WHERE u.role_id = 1 OR ur.id IS NOT NULL;

-- ------------------------------------------------------------------------------
-- 2. EKSEKUSI PENCABUTAN AKSES ADMIN
-- ------------------------------------------------------------------------------
START TRANSACTION;

-- A. Hapus role Super Admin (role_id = 1) dari tabel `user_roles` untuk semua Wali Kelas
DELETE ur 
FROM user_roles ur
JOIN guru_tendik gt ON gt.user_id = ur.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE ur.role_id = 1;

-- B. Kembalikan role_id default di tabel `users` menjadi 2 (Guru)
UPDATE users u
JOIN guru_tendik gt ON gt.user_id = u.id
JOIN rombel r ON r.wali_kelas_id = gt.id
SET u.role_id = 2
WHERE u.role_id = 1;

-- C. Pastikan setiap Wali Kelas memiliki role Guru (role_id = 2) di tabel `user_roles`
INSERT INTO user_roles (user_id, role_id)
SELECT DISTINCT u.id, 2
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE NOT EXISTS (
    SELECT 1 FROM user_roles ur2 
    WHERE ur2.user_id = u.id AND ur2.role_id = 2
);

-- D. Pastikan setiap Wali Kelas memiliki role Wali Kelas (role_id = 3) di tabel `user_roles`
INSERT INTO user_roles (user_id, role_id)
SELECT DISTINCT u.id, 3
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE NOT EXISTS (
    SELECT 1 FROM user_roles ur3 
    WHERE ur3.user_id = u.id AND ur3.role_id = 3
);

COMMIT;

-- ------------------------------------------------------------------------------
-- 3. VERIFIKASI AKHIR (HASIL HARUS 0 ROWS)
-- ------------------------------------------------------------------------------
SELECT 
    u.id AS user_id, 
    u.username, 
    gt.nama_lengkap AS nama_guru, 
    u.role_id AS default_role_id,
    ur.role_id AS role_id_user_roles
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.role_id = 1
WHERE u.role_id = 1 OR ur.role_id = 1;
