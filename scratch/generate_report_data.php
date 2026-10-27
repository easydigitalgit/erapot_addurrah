<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// 1. STATISTIK PER KELAS TAHUN AJARAN 2026/2027 GANJIL
$rombelStats = $pdo->query("
    SELECT 
        r.id AS rombel_id, r.nama_rombel, r.tingkat,
        COUNT(DISTINCT s_loc.id) AS siswa_aktif_loc,
        COUNT(DISTINCT s_vps.id) AS siswa_aktif_vps,
        COUNT(ar_loc.id) AS ar_total_loc,
        COUNT(ar_vps.id) AS ar_total_vps,
        COUNT(DISTINCT ar_loc.siswa_id) AS ar_uniq_loc,
        COUNT(DISTINCT ar_vps.siswa_id) AS ar_uniq_vps
    FROM raporsmpit.rombel r
    LEFT JOIN raporsmpit.siswa s_loc ON s_loc.rombel_id = r.id AND s_loc.status_siswa = 'Aktif'
    LEFT JOIN raporsmpit_vps.siswa s_vps ON s_vps.rombel_id = r.id AND s_vps.status_siswa = 'Aktif'
    LEFT JOIN raporsmpit.anggota_rombel ar_loc ON ar_loc.rombel_id = r.id
    LEFT JOIN raporsmpit_vps.anggota_rombel ar_vps ON ar_vps.rombel_id = r.id
    WHERE r.id_tahun_ajaran = 10
    GROUP BY r.id, r.nama_rombel, r.tingkat
    ORDER BY r.tingkat, r.nama_rombel
")->fetchAll();

file_put_contents('scratch/rombel_stats.json', json_encode($rombelStats, JSON_PRETTY_PRINT));

// 2. DETAIL 24 SISWA HILANG
$missingSiswa = $pdo->query("
    SELECT l.id, l.rombel_id, r.nama_rombel, l.nis, l.nisn, l.nama_lengkap, l.jenis_kelamin,
           (SELECT COUNT(*) FROM raporsmpit.nilai_sumatif ns WHERE ns.siswa_id = l.id) AS sumatif_count,
           (SELECT COUNT(*) FROM raporsmpit.nilai_formatif nf WHERE nf.siswa_id = l.id) AS formatif_count,
           (SELECT COUNT(*) FROM raporsmpit.nilai_rapor nr WHERE nr.siswa_id = l.id) AS rapor_count
    FROM raporsmpit.siswa l
    LEFT JOIN raporsmpit_vps.siswa v ON l.id = v.id
    LEFT JOIN raporsmpit.rombel r ON l.rombel_id = r.id
    WHERE v.id IS NULL
    ORDER BY r.nama_rombel, l.nama_lengkap
")->fetchAll();

file_put_contents('scratch/missing_siswa_detail.json', json_encode($missingSiswa, JSON_PRETTY_PRINT));

// 3. DETAIL LM HILANG DI TINGKAT 8 DAN 9
$missingLm = $pdo->query("
    SELECT l.id, l.mapel_id, m.nama_mapel, l.tingkat, l.semester, l.kategori, l.kode_lm, l.deskripsi_lm
    FROM raporsmpit.master_lm l
    LEFT JOIN raporsmpit_vps.master_lm v ON l.id = v.id
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    WHERE v.id IS NULL
    ORDER BY l.tingkat, l.semester, l.kategori, m.nama_mapel, l.kode_lm
")->fetchAll();

file_put_contents('scratch/missing_lm_detail.json', json_encode($missingLm, JSON_PRETTY_PRINT));

echo "Data export finished successfully.\n";

