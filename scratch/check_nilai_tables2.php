<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
echo "nilai_komponen:\n";
$stmt = $pdo->query("DESCRIBE nilai_komponen");
while($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}
echo "\nnilai_rapor:\n";
$stmt = $pdo->query("DESCRIBE nilai_rapor");
while($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}

