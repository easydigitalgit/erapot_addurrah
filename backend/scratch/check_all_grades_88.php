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

    echo "=== ALL FORMATIF GRADES FOR SISWA 88 ===\n";
    $stmt = $pdo->prepare("
        SELECT nf.id, nf.mapel_id, mp.nama_mapel, nf.rombel_id, r.nama_rombel, nf.tahun_ajaran_id, nf.semester, nf.created_at, nf.updated_at
        FROM nilai_formatif nf
        LEFT JOIN mata_pelajaran mp ON mp.id = nf.mapel_id
        LEFT JOIN rombel r ON r.id = nf.rombel_id
        WHERE nf.siswa_id = ?
    ");
    $stmt->execute([$student_id]);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\n=== ALL SUMATIF GRADES FOR SISWA 88 ===\n";
    $stmt2 = $pdo->prepare("
        SELECT ns.id, ns.mapel_id, mp.nama_mapel, ns.tahun_ajaran_id, ns.kategori, ns.jenis_sumatif, ns.nilai, ns.created_at, ns.updated_at
        FROM nilai_sumatif ns
        LEFT JOIN mata_pelajaran mp ON mp.id = ns.mapel_id
        WHERE ns.siswa_id = ?
    ");
    $stmt2->execute([$student_id]);
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
