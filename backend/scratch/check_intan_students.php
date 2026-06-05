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

    echo "=== STUDENTS IN ANGGOTA_ROMBEL FOR INTAN (Rombel 18) ===\n";
    $stmt = $pdo->prepare("
        SELECT ar.id as ar_id, ar.siswa_id, s.nis, s.nama_lengkap 
        FROM anggota_rombel ar
        JOIN siswa s ON s.id = ar.siswa_id
        WHERE ar.rombel_id = 18
        ORDER BY s.nama_lengkap ASC
    ");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Count: " . count($students) . "\n";
    foreach ($students as $s) {
        echo "AR_ID: {$s['ar_id']}, Siswa_ID: {$s['siswa_id']}, NIS: {$s['nis']}, Name: {$s['nama_lengkap']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
