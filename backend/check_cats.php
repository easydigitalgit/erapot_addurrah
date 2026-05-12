<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT DISTINCT kategori, COUNT(*) as count FROM nilai_formatif WHERE rombel_id=16 AND mapel_id=3 GROUP BY kategori");
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
