<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

try {
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
        ':kode_lm'         => 'LM 99',
        ':deskripsi_lm'    => 'Test',
        ':deskripsi_a'     => 'Test A',
        ':deskripsi_b'     => 'Test B',
        ':deskripsi_c'     => 'Test C',
        ':deskripsi_d'     => 'Test D',
        ':status'          => 'Aktif'
    ]);
    echo "Insert successful!\n";
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
