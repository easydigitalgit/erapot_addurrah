<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$report = [];

// 1. TAHUN AJARAN AKTIF
$taLocal = $pdo->query("SELECT * FROM raporsmpit.tahun_ajaran WHERE status = 'Aktif'")->fetch();
$taVps   = $pdo->query("SELECT * FROM raporsmpit_vps.tahun_ajaran WHERE status = 'Aktif'")->fetch();
$allTaLocal = $pdo->query("SELECT * FROM raporsmpit.tahun_ajaran ORDER BY id")->fetchAll();
$allTaVps   = $pdo->query("SELECT * FROM raporsmpit_vps.tahun_ajaran ORDER BY id")->fetchAll();

$report['tahun_ajaran'] = [
    'local_active' => $taLocal,
    'vps_active'   => $taVps,
    'local_all'    => $allTaLocal,
    'vps_all'      => $allTaVps,
];

// 2. ROMBEL
$rombelLocal = $pdo->query("SELECT id, nama_rombel, tingkat, id_tahun_ajaran, wali_kelas_id FROM raporsmpit.rombel ORDER BY id_tahun_ajaran, tingkat, nama_rombel")->fetchAll();
$rombelVps   = $pdo->query("SELECT id, nama_rombel, tingkat, id_tahun_ajaran, wali_kelas_id FROM raporsmpit_vps.rombel ORDER BY id_tahun_ajaran, tingkat, nama_rombel")->fetchAll();
$report['rombel'] = [
    'local_count' => count($rombelLocal),
    'vps_count'   => count($rombelVps),
    'diff'        => []
];

// Map rombel by id
$rombelLocalById = [];
foreach ($rombelLocal as $r) $rombelLocalById[$r['id']] = $r;
$rombelVpsById = [];
foreach ($rombelVps as $r) $rombelVpsById[$r['id']] = $r;

foreach ($rombelLocalById as $id => $rLoc) {
    if (!isset($rombelVpsById[$id])) {
        $report['rombel']['diff'][] = "Rombel ID $id ada di Lokal ({$rLoc['nama_rombel']}, Tgt {$rLoc['tingkat']}, TA {$rLoc['id_tahun_ajaran']}), TIDAK ADA di VPS";
    } else {
        $rVps = $rombelVpsById[$id];
        $changes = [];
        if ($rLoc['nama_rombel'] !== $rVps['nama_rombel']) $changes[] = "nama: '{$rLoc['nama_rombel']}' vs VPS '{$rVps['nama_rombel']}'";
        if ($rLoc['tingkat'] !== $rVps['tingkat']) $changes[] = "tingkat: {$rLoc['tingkat']} vs VPS {$rVps['tingkat']}";
        if ($rLoc['id_tahun_ajaran'] !== $rVps['id_tahun_ajaran']) $changes[] = "TA: {$rLoc['id_tahun_ajaran']} vs VPS {$rVps['id_tahun_ajaran']}";
        if ($rLoc['wali_kelas_id'] !== $rVps['wali_kelas_id']) $changes[] = "wali: {$rLoc['wali_kelas_id']} vs VPS {$rVps['wali_kelas_id']}";
        if ($changes) {
            $report['rombel']['diff'][] = "Rombel ID $id beda: " . implode(', ', $changes);
        }
    }
}
foreach ($rombelVpsById as $id => $rVps) {
    if (!isset($rombelLocalById[$id])) {
        $report['rombel']['diff'][] = "Rombel ID $id ada di VPS ({$rVps['nama_rombel']}, Tgt {$rVps['tingkat']}, TA {$rVps['id_tahun_ajaran']}), TIDAK ADA di Lokal";
    }
}

// 3. SISWA
// Cek siswa yang ada di Lokal tapi tidak ada di VPS
$missingInVps = $pdo->query("
    SELECT l.id, l.nis, l.nisn, l.nama_lengkap, l.status_siswa, l.rombel_id 
    FROM raporsmpit.siswa l
    LEFT JOIN raporsmpit_vps.siswa v ON l.id = v.id
    WHERE v.id IS NULL
    ORDER BY l.id
")->fetchAll();

// Cek siswa yang ada di VPS tapi tidak ada di Lokal
$missingInLocal = $pdo->query("
    SELECT v.id, v.nis, v.nisn, v.nama_lengkap, v.status_siswa, v.rombel_id 
    FROM raporsmpit_vps.siswa v
    LEFT JOIN raporsmpit.siswa l ON v.id = l.id
    WHERE l.id IS NULL
    ORDER BY v.id
")->fetchAll();

// Cek siswa dengan ID sama tapi beda NIS/Nama
$mismatchedSiswa = $pdo->query("
    SELECT l.id, l.nis AS nis_loc, v.nis AS nis_vps, 
           l.nama_lengkap AS nama_loc, v.nama_lengkap AS nama_vps,
           l.nisn AS nisn_loc, v.nisn AS nisn_vps,
           l.status_siswa AS status_loc, v.status_siswa AS status_vps,
           l.rombel_id AS rombel_loc, v.rombel_id AS rombel_vps
    FROM raporsmpit.siswa l
    JOIN raporsmpit_vps.siswa v ON l.id = v.id
    WHERE l.nis != v.nis OR l.nama_lengkap != v.nama_lengkap OR l.status_siswa != v.status_siswa OR COALESCE(l.rombel_id,0) != COALESCE(v.rombel_id,0)
    ORDER BY l.id
")->fetchAll();

$report['siswa'] = [
    'local_count' => (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit.siswa")->fetchColumn(),
    'vps_count'   => (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.siswa")->fetchColumn(),
    'missing_in_vps_count' => count($missingInVps),
    'missing_in_vps' => $missingInVps,
    'missing_in_local_count' => count($missingInLocal),
    'missing_in_local' => $missingInLocal,
    'mismatched_count' => count($mismatchedSiswa),
    'mismatched_sample' => array_slice($mismatchedSiswa, 0, 20)
];

// Siswa per Rombel pada Tahun Ajaran Aktif
$taAktifId = $taLocal['id'] ?? 10;
$anggotaRombelSummary = $pdo->query("
    SELECT r.id AS rombel_id, r.nama_rombel, r.tingkat,
           (SELECT COUNT(*) FROM raporsmpit.anggota_rombel ar WHERE ar.rombel_id = r.id) AS jml_ar_local,
           (SELECT COUNT(*) FROM raporsmpit_vps.anggota_rombel ar WHERE ar.rombel_id = r.id) AS jml_ar_vps,
           (SELECT COUNT(*) FROM raporsmpit.siswa s WHERE s.rombel_id = r.id AND s.status_siswa = 'Aktif') AS jml_siswa_col_local,
           (SELECT COUNT(*) FROM raporsmpit_vps.siswa s WHERE s.rombel_id = r.id AND s.status_siswa = 'Aktif') AS jml_siswa_col_vps
    FROM raporsmpit.rombel r
    WHERE r.id_tahun_ajaran = $taAktifId
    ORDER BY r.tingkat, r.nama_rombel
")->fetchAll();
$report['siswa_per_rombel_aktif'] = $anggotaRombelSummary;

// 4. MASTER LM
// Distribusi LM per tingkat & kategori & semester
$lmDistLocal = $pdo->query("
    SELECT tingkat, semester, kategori, COUNT(*) as jml
    FROM raporsmpit.master_lm
    GROUP BY tingkat, semester, kategori
    ORDER BY tingkat, semester, kategori
")->fetchAll();

$lmDistVps = $pdo->query("
    SELECT tingkat, semester, kategori, COUNT(*) as jml
    FROM raporsmpit_vps.master_lm
    GROUP BY tingkat, semester, kategori
    ORDER BY tingkat, semester, kategori
")->fetchAll();

// LM yang ada di lokal tapi tidak ada di VPS
$lmMissingInVps = $pdo->query("
    SELECT l.id, l.tahun_ajaran_id, l.mapel_id, m.nama_mapel, l.tingkat, l.semester, l.kategori, l.kode_lm, l.deskripsi_lm
    FROM raporsmpit.master_lm l
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    LEFT JOIN raporsmpit_vps.master_lm v ON l.id = v.id
    WHERE v.id IS NULL
    ORDER BY l.mapel_id, l.tingkat, l.kategori, l.kode_lm
")->fetchAll();

// LM yang ada di VPS tapi tidak ada di Lokal
$lmMissingInLocal = $pdo->query("
    SELECT v.id, v.tahun_ajaran_id, v.mapel_id, m.nama_mapel, v.tingkat, v.semester, v.kategori, v.kode_lm, v.deskripsi_lm
    FROM raporsmpit_vps.master_lm v
    LEFT JOIN raporsmpit_vps.mata_pelajaran m ON v.mapel_id = m.id
    LEFT JOIN raporsmpit.master_lm l ON v.id = l.id
    WHERE l.id IS NULL
    ORDER BY v.mapel_id, v.tingkat, v.kategori, v.kode_lm
")->fetchAll();

// LM dengan ID sama tapi beda deskripsi/kategori/tingkat
$lmDiff = $pdo->query("
    SELECT l.id, l.mapel_id, m.nama_mapel,
           l.tingkat AS tingkat_loc, v.tingkat AS tingkat_vps,
           l.semester AS sem_loc, v.semester AS sem_vps,
           l.kategori AS kat_loc, v.kategori AS kat_vps,
           l.kode_lm AS kode_loc, v.kode_lm AS kode_vps,
           l.deskripsi_lm AS desc_loc, v.deskripsi_lm AS desc_vps
    FROM raporsmpit.master_lm l
    JOIN raporsmpit_vps.master_lm v ON l.id = v.id
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    WHERE l.tingkat != v.tingkat OR l.semester != v.semester OR l.kategori != v.kategori 
          OR l.kode_lm != v.kode_lm OR l.deskripsi_lm != v.deskripsi_lm
")->fetchAll();

$report['master_lm'] = [
    'local_total' => (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit.master_lm")->fetchColumn(),
    'vps_total'   => (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.master_lm")->fetchColumn(),
    'dist_local'  => $lmDistLocal,
    'dist_vps'    => $lmDistVps,
    'missing_in_vps_count' => count($lmMissingInVps),
    'missing_in_vps' => $lmMissingInVps,
    'missing_in_local_count' => count($lmMissingInLocal),
    'missing_in_local' => $lmMissingInLocal,
    'diff_content_count' => count($lmDiff),
    'diff_content' => $lmDiff
];

// 5. NILAI SUMATIF
// Perbandingan per tahun ajaran
$sumatifByTa = $pdo->query("
    SELECT 
        COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id) AS ta_id,
        ta.tahun,
        COALESCE(l.jml_lokal, 0) AS jml_lokal,
        COALESCE(v.jml_vps, 0) AS jml_vps,
        (COALESCE(l.jml_lokal, 0) - COALESCE(v.jml_vps, 0)) AS selisih
    FROM (
        SELECT tahun_ajaran_id, COUNT(*) as jml_lokal 
        FROM raporsmpit.nilai_sumatif 
        GROUP BY tahun_ajaran_id
    ) l
    LEFT JOIN (
        SELECT tahun_ajaran_id, COUNT(*) as jml_vps 
        FROM raporsmpit_vps.nilai_sumatif 
        GROUP BY tahun_ajaran_id
    ) v ON l.tahun_ajaran_id = v.tahun_ajaran_id
    LEFT JOIN raporsmpit.tahun_ajaran ta ON ta.id = COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id)
    ORDER BY ta_id
")->fetchAll();

// Cek nilai sumatif pada TA Aktif per mapel
$sumatifMapelAktif = $pdo->query("
    SELECT 
        m.id AS mapel_id, m.nama_mapel,
        COALESCE(loc.jml, 0) AS jml_lokal,
        COALESCE(vps.jml, 0) AS jml_vps,
        (COALESCE(loc.jml, 0) - COALESCE(vps.jml, 0)) AS selisih
    FROM raporsmpit.mata_pelajaran m
    LEFT JOIN (
        SELECT mapel_id, COUNT(*) as jml 
        FROM raporsmpit.nilai_sumatif 
        WHERE tahun_ajaran_id = $taAktifId 
        GROUP BY mapel_id
    ) loc ON m.id = loc.mapel_id
    LEFT JOIN (
        SELECT mapel_id, COUNT(*) as jml 
        FROM raporsmpit_vps.nilai_sumatif 
        WHERE tahun_ajaran_id = $taAktifId 
        GROUP BY mapel_id
    ) vps ON m.id = vps.mapel_id
    WHERE COALESCE(loc.jml, 0) > 0 OR COALESCE(vps.jml, 0) > 0
    ORDER BY m.id
")->fetchAll();

$report['nilai_sumatif'] = [
    'by_ta' => $sumatifByTa,
    'ta_aktif_by_mapel' => $sumatifMapelAktif
];

// 6. NILAI FORMATIF
$formatifByTa = $pdo->query("
    SELECT 
        COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id) AS ta_id,
        ta.tahun,
        COALESCE(l.jml_lokal, 0) AS jml_lokal,
        COALESCE(v.jml_vps, 0) AS jml_vps,
        (COALESCE(l.jml_lokal, 0) - COALESCE(v.jml_vps, 0)) AS selisih
    FROM (
        SELECT tahun_ajaran_id, COUNT(*) as jml_lokal 
        FROM raporsmpit.nilai_formatif 
        GROUP BY tahun_ajaran_id
    ) l
    LEFT JOIN (
        SELECT tahun_ajaran_id, COUNT(*) as jml_vps 
        FROM raporsmpit_vps.nilai_formatif 
        GROUP BY tahun_ajaran_id
    ) v ON l.tahun_ajaran_id = v.tahun_ajaran_id
    LEFT JOIN raporsmpit.tahun_ajaran ta ON ta.id = COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id)
    ORDER BY ta_id
")->fetchAll();

$formatifMapelAktif = $pdo->query("
    SELECT 
        m.id AS mapel_id, m.nama_mapel,
        COALESCE(loc.jml, 0) AS jml_lokal,
        COALESCE(vps.jml, 0) AS jml_vps,
        (COALESCE(loc.jml, 0) - COALESCE(vps.jml, 0)) AS selisih
    FROM raporsmpit.mata_pelajaran m
    LEFT JOIN (
        SELECT mapel_id, COUNT(*) as jml 
        FROM raporsmpit.nilai_formatif 
        WHERE tahun_ajaran_id = $taAktifId 
        GROUP BY mapel_id
    ) loc ON m.id = loc.mapel_id
    LEFT JOIN (
        SELECT mapel_id, COUNT(*) as jml 
        FROM raporsmpit_vps.nilai_formatif 
        WHERE tahun_ajaran_id = $taAktifId 
        GROUP BY mapel_id
    ) vps ON m.id = vps.mapel_id
    WHERE COALESCE(loc.jml, 0) > 0 OR COALESCE(vps.jml, 0) > 0
    ORDER BY m.id
")->fetchAll();

$report['nilai_formatif'] = [
    'by_ta' => $formatifByTa,
    'ta_aktif_by_mapel' => $formatifMapelAktif
];

// 7. NILAI RAPOR
$raporByTa = $pdo->query("
    SELECT 
        COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id) AS ta_id,
        ta.tahun,
        COALESCE(l.jml_lokal, 0) AS jml_lokal,
        COALESCE(v.jml_vps, 0) AS jml_vps,
        (COALESCE(l.jml_lokal, 0) - COALESCE(v.jml_vps, 0)) AS selisih
    FROM (
        SELECT tahun_ajaran_id, COUNT(*) as jml_lokal 
        FROM raporsmpit.nilai_rapor 
        GROUP BY tahun_ajaran_id
    ) l
    LEFT JOIN (
        SELECT tahun_ajaran_id, COUNT(*) as jml_vps 
        FROM raporsmpit_vps.nilai_rapor 
        GROUP BY tahun_ajaran_id
    ) v ON l.tahun_ajaran_id = v.tahun_ajaran_id
    LEFT JOIN raporsmpit.tahun_ajaran ta ON ta.id = COALESCE(l.tahun_ajaran_id, v.tahun_ajaran_id)
    ORDER BY ta_id
")->fetchAll();

$report['nilai_rapor'] = [
    'by_ta' => $raporByTa
];

// 8. CEK ORPHANED NILAI (nilai yang mengarah ke siswa_id yang tidak ada di siswa)
$orphanSumatifLocal = $pdo->query("SELECT COUNT(*) FROM raporsmpit.nilai_sumatif ns LEFT JOIN raporsmpit.siswa s ON ns.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();
$orphanSumatifVps   = $pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.nilai_sumatif ns LEFT JOIN raporsmpit_vps.siswa s ON ns.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();

$orphanFormatifLocal = $pdo->query("SELECT COUNT(*) FROM raporsmpit.nilai_formatif nf LEFT JOIN raporsmpit.siswa s ON nf.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();
$orphanFormatifVps   = $pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.nilai_formatif nf LEFT JOIN raporsmpit_vps.siswa s ON nf.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();

$orphanRaporLocal = $pdo->query("SELECT COUNT(*) FROM raporsmpit.nilai_rapor nr LEFT JOIN raporsmpit.siswa s ON nr.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();
$orphanRaporVps   = $pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.nilai_rapor nr LEFT JOIN raporsmpit_vps.siswa s ON nr.siswa_id = s.id WHERE s.id IS NULL")->fetchColumn();

$report['orphans'] = [
    'sumatif' => ['local' => $orphanSumatifLocal, 'vps' => $orphanSumatifVps],
    'formatif' => ['local' => $orphanFormatifLocal, 'vps' => $orphanFormatifVps],
    'rapor' => ['local' => $orphanRaporLocal, 'vps' => $orphanRaporVps],
];

file_put_contents('scratch/detailed_report.json', json_encode($report, JSON_PRETTY_PRINT));
echo "Detailed report written to scratch/detailed_report.json" . PHP_EOL;
