<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$sumatifDiff = $pdo->query("
    SELECT l.tahun_ajaran_id, ta.tahun, ta.semester, l.mapel_id, m.nama_mapel, COUNT(*) as cnt
    FROM raporsmpit.nilai_sumatif l
    LEFT JOIN raporsmpit_vps.nilai_sumatif v ON l.id = v.id
    LEFT JOIN raporsmpit.tahun_ajaran ta ON l.tahun_ajaran_id = ta.id
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    WHERE v.id IS NULL
    GROUP BY l.tahun_ajaran_id, l.mapel_id
    ORDER BY l.tahun_ajaran_id, cnt DESC
")->fetchAll();

echo "Nilai sumatif di Lokal tapi TIDAK ADA di VPS (berdasarkan ID):\n";
foreach ($sumatifDiff as $d) {
    echo "  TA {$d['tahun']} {$d['semester']} (ID {$d['tahun_ajaran_id']}) | Mapel {$d['nama_mapel']} (ID {$d['mapel_id']}): {$d['cnt']} rows\n";
}

$sumatifVpsExtra = $pdo->query("
    SELECT v.tahun_ajaran_id, ta.tahun, ta.semester, v.mapel_id, m.nama_mapel, COUNT(*) as cnt
    FROM raporsmpit_vps.nilai_sumatif v
    LEFT JOIN raporsmpit.nilai_sumatif l ON v.id = l.id
    LEFT JOIN raporsmpit_vps.tahun_ajaran ta ON v.tahun_ajaran_id = ta.id
    LEFT JOIN raporsmpit_vps.mata_pelajaran m ON v.mapel_id = m.id
    WHERE l.id IS NULL
    GROUP BY v.tahun_ajaran_id, v.mapel_id
    ORDER BY v.tahun_ajaran_id, cnt DESC
")->fetchAll();

echo "\nNilai sumatif di VPS tapi TIDAK ADA di Lokal (berdasarkan ID):\n";
foreach ($sumatifVpsExtra as $d) {
    echo "  TA {$d['tahun']} {$d['semester']} (ID {$d['tahun_ajaran_id']}) | Mapel {$d['nama_mapel']} (ID {$d['mapel_id']}): {$d['cnt']} rows\n";
}

