<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();

$rombel = $db->table('rombel')->like('nama_rombel', 'Granit')->get()->getRowArray();
echo "ROMBEL: " . json_encode($rombel) . "\n";

$mapel = $db->table('mata_pelajaran')->like('nama_mapel', 'Inggris')->get()->getRowArray();
echo "MAPEL: " . json_encode($mapel) . "\n";

$ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
echo "TA AKTIF: " . json_encode($ta) . "\n";

if ($rombel && $ta) {
    $students = $db->table('anggota_rombel')
        ->where('rombel_id', $rombel['id'])
        ->where('tahun_ajaran_id', $ta['id'])
        ->where('semester', $ta['semester'])
        ->countAllResults();
    echo "STUDENTS IN ROMBEL (ACTIVE TA): " . $students . "\n";
    
    if ($mapel) {
        $grades = $db->table('nilai_formatif')
            ->where('rombel_id', $rombel['id'])
            ->where('mapel_id', $mapel['id'])
            ->where('tahun_ajaran_id', $ta['id'])
            ->where('semester', $ta['semester'])
            ->countAllResults();
        echo "GRADES IN nilai_formatif: " . $grades . "\n";
        
        $sumatif = $db->table('nilai_sumatif')
            ->where('mapel_id', $mapel['id'])
            ->where('tahun_ajaran_id', $ta['id'])
            ->countAllResults();
        echo "GRADES IN nilai_sumatif: " . $sumatif . "\n";
    }
}
