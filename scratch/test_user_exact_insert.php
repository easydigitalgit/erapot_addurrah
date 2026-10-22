<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->query("SHOW COLUMNS FROM master_lm LIKE 'tahun_ajaran_id'");
$col = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Current Nullable: " . $col['Null'] . "\n";

// Test inserting what user had in modal:
$stmt = $pdo->prepare("
    INSERT INTO master_lm (
        tahun_ajaran_id, mapel_id, tingkat, semester, kategori, kode_lm, deskripsi_lm, deskripsi_a, deskripsi_b, deskripsi_c, deskripsi_d, status
    ) VALUES (
        :tahun_ajaran_id, :mapel_id, :tingkat, :semester, :kategori, :kode_lm, :deskripsi_lm, :deskripsi_a, :deskripsi_b, :deskripsi_c, :deskripsi_d, :status
    )
");

$res = $stmt->execute([
    ':tahun_ajaran_id' => null,
    ':mapel_id'        => 44, // Nahwu
    ':tingkat'         => 9,
    ':semester'        => 'Ganjil',
    ':kategori'        => 'Tengah',
    ':kode_lm'         => 'LM 1',
    ':deskripsi_lm'    => 'Perubahan Bentuk Kata Kerja',
    ':deskripsi_a'     => 'Menunjukkan penguasaan yang sangat baik dalam perubahan bentuk kata kerja.',
    ':deskripsi_b'     => 'Menunjukkan penguasaan yang baik dalam perubahan bentuk kata kerja.',
    ':deskripsi_c'     => 'cukup menguasai dalam perubahan bentuk kata kerja',
    ':deskripsi_d'     => 'Kurang menguasai dalam perubahan bentuk kata kerja',
    ':status'          => 'Aktif'
]);

$newId = $pdo->lastInsertId();
echo "Inserted record ID: $newId\n";

$fetch = $pdo->query("SELECT * FROM master_lm WHERE id = $newId")->fetch(PDO::FETCH_ASSOC);
print_r($fetch);
