<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query('DESCRIBE master_lm');
echo "=== COLUMNS OF master_lm ===\n";
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$r['Field']} | {$r['Type']} | Null: {$r['Null']} | Key: {$r['Key']} | Default: {$r['Default']}\n";
}

echo "\n=== CREATE TABLE master_lm ===\n";
$stmt2 = $pdo->query('SHOW CREATE TABLE master_lm');
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
echo $row['Create Table'] . "\n";
