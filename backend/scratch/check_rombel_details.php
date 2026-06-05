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

    echo "=== ALL ANGGOTA ROMBEL RECORDS FOR SISWA ID 88 ===\n";
    $stmt = $pdo->prepare("
        SELECT ar.*, r.nama_rombel, ta.tahun, ta.semester as ta_semester 
        FROM anggota_rombel ar
        JOIN rombel r ON r.id = ar.rombel_id
        JOIN tahun_ajaran ta ON ta.id = ar.tahun_ajaran_id
        WHERE ar.siswa_id = ?
    ");
    $stmt->execute([$student_id]);
    $anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($anggota);

    echo "\n=== DETAILS OF ROMBEL ID 18 ===\n";
    $stmt2 = $pdo->prepare("SELECT * FROM rombel WHERE id = 18");
    $stmt2->execute();
    print_r($stmt2->fetch(PDO::FETCH_ASSOC));

    echo "\n=== DETAILS OF ROMBEL ID 20 ===\n";
    $stmt3 = $pdo->prepare("SELECT * FROM rombel WHERE id = 20");
    $stmt3->execute();
    print_r($stmt3->fetch(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
