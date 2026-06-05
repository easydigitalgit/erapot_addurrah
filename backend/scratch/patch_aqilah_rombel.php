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

    $pdo->beginTransaction();

    echo "=== PATCHING DATABASE ===\n";
    
    // Update the mismatched formatif records for Aqilah (siswa_id = 88)
    $stmt = $pdo->prepare("
        UPDATE nilai_formatif 
        SET rombel_id = 20 
        WHERE siswa_id = 88 
          AND mapel_id = 5 
          AND rombel_id = 18 
          AND tahun_ajaran_id = 9 
          AND semester = 'Genap'
    ");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "Successfully updated $affected rows in nilai_formatif for Aqilah (siswa_id = 88) to rombel_id = 20.\n";

    $pdo->commit();

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
