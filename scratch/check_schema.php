<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "Columns in nilai_formatif:\n";
$stmt = $pdo->query("DESCRIBE nilai_formatif");
while($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}

echo "\nColumns in nilai_sumatif:\n";
$stmt = $pdo->query("DESCRIBE nilai_sumatif");
while($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}

