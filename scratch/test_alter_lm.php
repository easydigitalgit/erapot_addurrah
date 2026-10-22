<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

try {
    $pdo->exec("ALTER TABLE master_lm MODIFY COLUMN tahun_ajaran_id INT NULL DEFAULT NULL");
    echo "ALTER TABLE SUCCESS!\n";
    
    // Now test inserting with null
    $stmt = $pdo->prepare("
        INSERT INTO master_lm (
            tahun_ajaran_id, mapel_id, tingkat, semester, kategori, kode_lm, deskripsi_lm, deskripsi_a, deskripsi_b, deskripsi_c, deskripsi_d, status
        ) VALUES (
            :tahun_ajaran_id, :mapel_id, :tingkat, :semester, :kategori, :kode_lm, :deskripsi_lm, :deskripsi_a, :deskripsi_b, :deskripsi_c, :deskripsi_d, :status
        )
    ");
    
    $stmt->execute([
        ':tahun_ajaran_id' => null,
        ':mapel_id'        => 1,
        ':tingkat'         => 9,
        ':semester'        => 'Ganjil',
        ':kategori'        => 'Tengah',
        ':kode_lm'         => 'TEST_LM_NULL',
        ':deskripsi_lm'    => 'Test Materi',
        ':deskripsi_a'     => 'Test A',
        ':deskripsi_b'     => 'Test B',
        ':deskripsi_c'     => 'Test C',
        ':deskripsi_d'     => 'Test D',
        ':status'          => 'Aktif'
    ]);
    
    $insertId = $pdo->lastInsertId();
    echo "INSERT SUCCESS with ID: $insertId\n";
    
    // Clean up test record
    $pdo->exec("DELETE FROM master_lm WHERE id = $insertId");
    echo "CLEANUP SUCCESS!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
