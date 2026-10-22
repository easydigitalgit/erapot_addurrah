<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query("SHOW COLUMNS FROM master_lm LIKE 'tahun_ajaran_id'");
$col = $stmt->fetch(PDO::FETCH_ASSOC);
echo "tahun_ajaran_id is Nullable? " . $col['Null'] . "\n";
