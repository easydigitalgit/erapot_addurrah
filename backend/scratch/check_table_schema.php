<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== SCHEMA OF nilai_formatif ===\n";
    $stmt = $pdo->query("SHOW CREATE TABLE nilai_formatif");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));

    echo "\n=== SCHEMA OF nilai_sumatif ===\n";
    $stmt2 = $pdo->query("SHOW CREATE TABLE nilai_sumatif");
    print_r($stmt2->fetch(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
