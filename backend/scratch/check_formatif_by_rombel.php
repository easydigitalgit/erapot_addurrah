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

    echo "=== FORMATIF COUNT BY ROMBEL FOR SISWA 88 ===\n";
    $stmt = $pdo->prepare("
        SELECT nf.rombel_id, r.nama_rombel, nf.mapel_id, mp.nama_mapel, COUNT(*) as count 
        FROM nilai_formatif nf
        LEFT JOIN rombel r ON r.id = nf.rombel_id
        LEFT JOIN mata_pelajaran mp ON mp.id = nf.mapel_id
        WHERE nf.siswa_id = ?
        GROUP BY nf.rombel_id, r.nama_rombel, nf.mapel_id, mp.nama_mapel
    ");
    $stmt->execute([$student_id]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
