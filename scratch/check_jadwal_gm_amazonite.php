<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== JADWAL PELAJARAN ROMBEL 77 (VII Amazonite) ===\n";
$jp = $pdo->query("SELECT jp.*, m.nama_mapel FROM jadwal_pelajaran jp JOIN mata_pelajaran m ON m.id = jp.mapel_id WHERE jp.rombel_id = 77")->fetchAll(PDO::FETCH_ASSOC);
foreach ($jp as $row) {
    echo "- Mapel [{$row['mapel_id']}] {$row['nama_mapel']}\n";
}

echo "\n=== GURU MAPEL ROMBEL 77 (VII Amazonite) ===\n";
$gm = $pdo->query("SELECT gm.*, m.nama_mapel FROM guru_mapel gm JOIN mata_pelajaran m ON m.id = gm.mapel_id WHERE gm.rombel_id = 77")->fetchAll(PDO::FETCH_ASSOC);
foreach ($gm as $row) {
    echo "- Mapel [{$row['mapel_id']}] {$row['nama_mapel']}\n";
}

