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
    $tahun_ajaran_id = 9;
    $semester = 'Genap';

    echo "=== CURRENT DB RECORDS FOR AQILAH (Siswa ID 88, Mapel ID 5) ===\n";
    
    // Formatifs
    $stmt = $pdo->prepare("SELECT id, siswa_id, mapel_id, rombel_id, jenis_penilaian, pertemuan, nilai_angka, tahun_ajaran_id, semester, kategori FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = ?");
    $stmt->execute([$student_id, $mapel_id, $tahun_ajaran_id, $semester]);
    $formatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "nilai_formatif count: " . count($formatifs) . "\n";
    print_r($formatifs);

    // Sumatifs
    $stmt2 = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ?");
    $stmt2->execute([$student_id, $mapel_id, $tahun_ajaran_id]);
    $sumatifs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "nilai_sumatif count: " . count($sumatifs) . "\n";
    print_r($sumatifs);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
