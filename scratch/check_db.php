<?php
define('FCPATH', __DIR__ . '/backend/public/');
require 'backend/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();

echo "--- SETTING BOBOT NILAI ---\n";
$bobot = $db->table('setting_bobot_nilai')->get()->getResultArray();
print_r($bobot);

echo "\n--- NILAI SISWA (AISYAHARA INDRI) ---\n";
// AISYAHARA INDRI NISN: 0117905743
$siswa = $db->table('siswa')->where('nisn', '0117905743')->get()->getRowArray();
if ($siswa) {
    $siswa_id = $siswa['id'];
    echo "Siswa ID: $siswa_id\n";
    
    echo "\nSUMATIF:\n";
    $sumatif = $db->table('nilai_sumatif')->where('siswa_id', $siswa_id)->get()->getResultArray();
    print_r($sumatif);
    
    echo "\nFORMATIF:\n";
    $formatif = $db->table('nilai_formatif')->where('siswa_id', $siswa_id)->get()->getResultArray();
    print_r($formatif);
} else {
    echo "Siswa tidak ditemukan.\n";
}
