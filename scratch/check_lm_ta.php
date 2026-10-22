<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query("SELECT tahun_ajaran_id, COUNT(*) as count FROM master_lm GROUP BY tahun_ajaran_id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
