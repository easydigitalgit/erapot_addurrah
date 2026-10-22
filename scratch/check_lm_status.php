<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=raporsmpit;charset=utf8mb4", "root", "");
$stmt = $pdo->query("SELECT * FROM master_lm WHERE id IN (119, 120, 121)");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
