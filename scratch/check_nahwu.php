<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query("SELECT id, nama_mapel FROM mata_pelajaran WHERE nama_mapel LIKE '%Nahwu%'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
