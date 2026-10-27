<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');
$stmt = $pdo->query('DESCRIBE nilai_rapor');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . ' (' . $col['Type'] . ")\n";
}

