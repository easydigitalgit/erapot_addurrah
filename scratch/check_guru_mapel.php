<?php
require __DIR__ . '/../backend/vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== GURU MAPEL FOR GURU_ID = 51 ===\n";
$stmt = $pdo->prepare("SELECT gm.*, m.nama_mapel, r.nama_rombel, r.id_tahun_ajaran, r.tingkat 
FROM guru_mapel gm 
LEFT JOIN mata_pelajaran m ON m.id = gm.mapel_id 
LEFT JOIN rombel r ON r.id = gm.rombel_id 
WHERE gm.guru_id = 51");
$stmt->execute();
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($r) . "\n";
}

echo "\n=== GURU MAPEL FOR MAPEL 'Hadist' (id: 11) ===\n";
$stmt = $pdo->prepare("SELECT gm.*, m.nama_mapel, r.nama_rombel, r.id_tahun_ajaran, r.tingkat, gt.nama_lengkap 
FROM guru_mapel gm 
LEFT JOIN mata_pelajaran m ON m.id = gm.mapel_id 
LEFT JOIN rombel r ON r.id = gm.rombel_id 
LEFT JOIN guru_tendik gt ON gt.id = gm.guru_id
WHERE gm.mapel_id = 11");
$stmt->execute();
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($r) . "\n";
}
