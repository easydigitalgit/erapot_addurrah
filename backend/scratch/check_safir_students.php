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

    $rombel_id = 20; // Safir
    $tahun_ajaran_id = 9;
    $semester = 'Genap';
    $mapel_id = 5;

    echo "=== STUDENTS IN ANGGOTA_ROMBEL FOR SAFIR (Rombel 20, TA 9, Genap) ===\n";
    $stmt = $pdo->prepare("
        SELECT s.id, s.nis, s.nama_lengkap 
        FROM anggota_rombel ar
        JOIN siswa s ON s.id = ar.siswa_id
        WHERE ar.rombel_id = ? AND ar.tahun_ajaran_id = ? AND ar.semester = ?
        ORDER BY s.nama_lengkap ASC
    ");
    $stmt->execute([$rombel_id, $tahun_ajaran_id, $semester]);
    $ar_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Count: " . count($ar_students) . "\n";
    foreach ($ar_students as $idx => $s) {
        echo ($idx + 1) . ". ID: {$s['id']}, NIS: {$s['nis']}, Name: {$s['nama_lengkap']}\n";
    }

    echo "\n=== STUDENTS IN NILAI_FORMATIF FOR SAFIR (Rombel 20, Mapel 5, TA 9, Genap) ===\n";
    $stmt2 = $pdo->prepare("
        SELECT DISTINCT nf.siswa_id, s.nis, s.nama_lengkap
        FROM nilai_formatif nf
        JOIN siswa s ON s.id = nf.siswa_id
        WHERE nf.rombel_id = ? AND nf.mapel_id = ? AND nf.tahun_ajaran_id = ? AND nf.semester = ?
        ORDER BY s.nama_lengkap ASC
    ");
    $stmt2->execute([$rombel_id, $mapel_id, $tahun_ajaran_id, $semester]);
    $nf_students = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "Count: " . count($nf_students) . "\n";
    foreach ($nf_students as $idx => $s) {
        echo ($idx + 1) . ". ID: {$s['siswa_id']}, NIS: {$s['nis']}, Name: {$s['nama_lengkap']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
