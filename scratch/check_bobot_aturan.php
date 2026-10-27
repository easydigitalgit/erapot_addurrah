<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== setting_bobot_nilai ===\n";
$bobot = $pdo->query("SELECT * FROM setting_bobot_nilai")->fetchAll(PDO::FETCH_ASSOC);
print_r($bobot);

echo "\n=== setting_aturan_nilai ===\n";
$aturan = $pdo->query("SELECT * FROM setting_aturan_nilai")->fetchAll(PDO::FETCH_ASSOC);
print_r($aturan);
