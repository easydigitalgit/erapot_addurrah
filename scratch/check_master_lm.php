<?php
require __DIR__ . '/../backend/vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== MASTER LM FOR HADIST (11) ===\n";
$stmt = $pdo->prepare("SELECT * FROM master_lm WHERE mapel_id = 11");
$stmt->execute();
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($r) . "\n";
}
