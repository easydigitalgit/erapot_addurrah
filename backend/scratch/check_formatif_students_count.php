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

    $mapel_id = 5;

    echo "=== FORMATIF STUDENT COUNT BY ROMBEL FOR MAPEL $mapel_id ===\n";
    $stmt = $pdo->prepare("
        SELECT nf.rombel_id, r.nama_rombel, COUNT(DISTINCT nf.siswa_id) as student_count 
        FROM nilai_formatif nf
        LEFT JOIN rombel r ON r.id = nf.rombel_id
        WHERE nf.mapel_id = ?
        GROUP BY nf.rombel_id, r.nama_rombel
    ");
    $stmt->execute([$mapel_id]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
