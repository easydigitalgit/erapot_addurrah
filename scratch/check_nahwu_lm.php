<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query("SELECT * FROM master_lm WHERE mapel_id = 44");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
