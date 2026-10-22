<?php
require __DIR__ . '/../backend/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

// Bootstrap CI4
CodeIgniter\Boot::bootTest($paths);

$model = new \App\Models\Admin\MasterLmModel();

$data = [
    'tahun_ajaran_id' => null,
    'mapel_id'        => 44, // Nahwu
    'tingkat'         => 9,
    'semester'        => 'Ganjil',
    'kategori'        => 'Tengah',
    'kode_lm'         => 'LM 1',
    'deskripsi_lm'    => 'Perubahan Bentuk Kata Kerja',
    'deskripsi_a'     => 'Menunjukkan penguasaan yang sangat baik dalam perubahan bentuk kata kerja.',
    'deskripsi_b'     => 'Menunjukkan penguasaan yang baik dalam perubahan bentuk kata kerja.',
    'deskripsi_c'     => 'Cukup menguasai dalam perubahan bentuk kata kerja.',
    'deskripsi_d'     => 'Kurang menguasai dalam perubahan bentuk kata kerja.',
    'status'          => 'Aktif'
];

try {
    $insertedId = $model->insert($data);
    echo "INSERT VIA CI4 MODEL SUCCESS! ID = " . var_export($insertedId, true) . "\n";
    
    // Check data retrieved
    $saved = $model->find($insertedId);
    echo "RETRIEVED RECORD:\n";
    print_r($saved);

    // Test getAllLM
    $all = $model->getAllLM();
    $found = array_filter($all, fn($x) => $x['id'] == $insertedId);
    echo "FOUND IN getAllLM(): " . count($found) . " item(s)\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
