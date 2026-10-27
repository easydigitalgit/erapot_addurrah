<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$indexes = $pdo->query("SHOW INDEX FROM nilai_sumatif")->fetchAll(PDO::FETCH_ASSOC);
foreach ($indexes as $idx) {
    echo "Index: {$idx['Key_name']} | Column: {$idx['Column_name']} | Non_unique: {$idx['Non_unique']}\n";
}

