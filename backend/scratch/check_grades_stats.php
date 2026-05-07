<?php
// Script to debug the pembagi logic
require_once __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv($paths->projectDirectory))->load();
require_once $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();

$mapel_nama = "Ilmu Pengetahuan Alam";
$ta_id = 2; // I need to find the correct TA ID. Based on Image 1, it's 2025/2026 Genap.
// Let's first find the mapel ID and TA ID.

$mapel = $db->table('mata_pelajaran')->like('nama_mapel', $mapel_nama)->get()->getRowArray();
$ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

if (!$mapel) {
    echo "Mapel not found\n";
    exit;
}
if (!$ta) {
    echo "TA not found\n";
    exit;
}

echo "Mapel ID: " . $mapel['id'] . " (" . $mapel['nama_mapel'] . ")\n";
echo "TA ID: " . $ta['id'] . " (" . $ta['tahun'] . " " . $ta['semester'] . ")\n";

$rombel_zamrud = $db->table('rombel')->like('nama_rombel', 'Zamrud')->get()->getRowArray();
echo "Zamrud Rombel ID: " . ($rombel_zamrud['id'] ?? 'Not found') . "\n";

// Count formatifs per rombel and pertemuan
$stats = $db->table('nilai_formatif f')
    ->select('r.nama_rombel, f.rombel_id, f.pertemuan, f.jenis_penilaian, COUNT(*) as count')
    ->join('rombel r', 'r.id = f.rombel_id', 'left')
    ->where('f.mapel_id', $mapel['id'])
    ->where('f.tahun_ajaran_id', $ta['id'])
    ->groupBy('f.rombel_id, f.pertemuan, f.jenis_penilaian')
    ->get()->getResultArray();

echo "Formatif Stats:\n";
foreach ($stats as $s) {
    echo "Class: " . $s['nama_rombel'] . " | Pertemuan: " . $s['pertemuan'] . " | Jenis: " . $s['jenis_penilaian'] . " | Count: " . $s['count'] . "\n";
}

// Check max pertemuan across all classes for this mapel
$max_pert = $db->table('nilai_formatif')
    ->select('MAX(pertemuan) as max_p')
    ->where('mapel_id', $mapel['id'])
    ->where('tahun_ajaran_id', $ta['id'])
    ->get()->getRowArray();

echo "\nGlobal Max Pertemuan for this Mapel/TA: " . ($max_pert['max_p'] ?? 0) . "\n";
