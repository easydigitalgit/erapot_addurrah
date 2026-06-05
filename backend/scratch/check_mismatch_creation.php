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

    echo "=== GRADES CREATED ON 2026-05-04 22:07:45 ===\n";
    $stmt = $pdo->prepare("
        SELECT nf.id, nf.siswa_id, s.nama_lengkap, nf.rombel_id, r.nama_rombel, nf.mapel_id, nf.nilai_angka, nf.created_at
        FROM nilai_formatif nf
        JOIN siswa s ON s.id = nf.siswa_id
        JOIN rombel r ON r.id = nf.rombel_id
        WHERE nf.created_at = '2026-05-04 22:07:45'
    ");
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Count: " . count($grades) . "\n";
    foreach ($grades as $g) {
        echo "Siswa: {$g['nama_lengkap']} (ID: {$g['siswa_id']}), Rombel: {$g['nama_rombel']} (ID: {$g['rombel_id']}), Mapel: {$g['mapel_id']}, Nilai: {$g['nilai_angka']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
