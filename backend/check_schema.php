<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];
$pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}", $db_config['user'], $db_config['pass']);
$stmt = $pdo->query("DESCRIBE nilai_rapor");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
