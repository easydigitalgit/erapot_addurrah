<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');
echo "--- nilai_sumatif ---\n";
$stmt = $pdo->query('DESCRIBE nilai_sumatif');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . ' (' . $col['Type'] . ")\n";
}

echo "\n--- nilai_formatif ---\n";
$stmt = $pdo->query('DESCRIBE nilai_formatif');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . ' (' . $col['Type'] . ")\n";
}

