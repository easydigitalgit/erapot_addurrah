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

    echo "=== DISCREPANCIES BETWEEN nilai_formatif.rombel_id AND anggota_rombel.rombel_id ===\n";
    $stmt = $pdo->prepare("
        SELECT nf.id as formatif_id, nf.siswa_id, s.nama_lengkap, nf.mapel_id, mp.nama_mapel, 
               nf.rombel_id as formatif_rombel_id, r_nf.nama_rombel as formatif_rombel_nama,
               ar.rombel_id as anggota_rombel_id, r_ar.nama_rombel as anggota_rombel_nama,
               nf.tahun_ajaran_id, nf.semester
        FROM nilai_formatif nf
        JOIN siswa s ON s.id = nf.siswa_id
        JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id 
             AND ar.tahun_ajaran_id = nf.tahun_ajaran_id 
             AND ar.semester = nf.semester COLLATE utf8mb4_general_ci
        JOIN rombel r_nf ON r_nf.id = nf.rombel_id
        JOIN rombel r_ar ON r_ar.id = ar.rombel_id
        JOIN mata_pelajaran mp ON mp.id = nf.mapel_id
        WHERE nf.rombel_id != ar.rombel_id
    ");
    $stmt->execute();
    $mismatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total mismatches: " . count($mismatches) . "\n";
    foreach ($mismatches as $idx => $m) {
        echo ($idx + 1) . ". Siswa: {$m['nama_lengkap']} (ID: {$m['siswa_id']}), Mapel: {$m['nama_mapel']}, "
             . "Formatif Rombel: {$m['formatif_rombel_nama']} (ID: {$m['formatif_rombel_id']}), "
             . "Anggota Rombel: {$m['anggota_rombel_nama']} (ID: {$m['anggota_rombel_id']})\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
