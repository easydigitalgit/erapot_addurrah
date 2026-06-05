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

    $student_id = 88;
    $mapel_id = 5;

    echo "=== FORMATIF GRADES ===\n";
    $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ?");
    $stmt->execute([$student_id, $mapel_id]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "=== SUMATIF GRADES ===\n";
    $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ?");
    $stmt->execute([$student_id, $mapel_id]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
