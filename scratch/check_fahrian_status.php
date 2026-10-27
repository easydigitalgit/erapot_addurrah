<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$f = $pdo->query("SELECT * FROM siswa WHERE id = 12")->fetch(PDO::FETCH_ASSOC);
echo "Status Fahrian: {$f['status_siswa']}\n";
print_r($f);

