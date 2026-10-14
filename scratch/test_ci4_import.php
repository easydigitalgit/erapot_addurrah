<?php
use CodeIgniter\Boot;
use Config\Paths;

define('FCPATH', __DIR__ . '/../backend/public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require FCPATH . '../app/Config/Paths.php';
$paths = new Paths();
require $paths->systemDirectory . '/Boot.php';
Boot::bootTest($paths);

$db = \Config\Database::connect();

// Mock session
$session = \Config\Services::session();
$session->set(['id' => 30, 'role_id' => 2]);

// Test Controller instantiation
$controller = new \App\Controllers\GuruMapel\NilaiKolektifController();

echo "Controller instantiated successfully!\n";

// Let's inspect the import query directly using CI4 db connection as controller does
$strKelas = 'Intan';
$strMapel = 'Hadist';
$tahun_ajaran_id = 10;

$namaKelas = trim(preg_replace('/^(kelas|rombel)\s*/i', '', $strKelas));
$rombelQuery = $db->table('rombel')->like('nama_rombel', $namaKelas);
if ($db->fieldExists('id_tahun_ajaran', 'rombel')) {
    $rombelQuery->where('id_tahun_ajaran', $tahun_ajaran_id);
}
$rombel = $rombelQuery->get()->getRowArray();
echo "CI4 DB Query Rombel: ID={$rombel['id']}, Name={$rombel['nama_rombel']}, TA={$rombel['id_tahun_ajaran']}\n";

$namaMapel = trim(preg_replace('/^(mapel|mata pelajaran)\s*/i', '', $strMapel));
$mapel = $db->table('mata_pelajaran')->like('nama_mapel', $namaMapel)->get()->getRowArray();
echo "CI4 DB Query Mapel: ID={$mapel['id']}, Name={$mapel['nama_mapel']}\n";

// Check students count in this rombel
$students = $db->table('anggota_rombel ar')
    ->select('s.id, s.nis, s.nama_lengkap')
    ->join('siswa s', 's.id = ar.siswa_id')
    ->where('ar.rombel_id', $rombel['id'])
    ->where('ar.tahun_ajaran_id', $tahun_ajaran_id)
    ->get()->getResultArray();
echo "Found " . count($students) . " students in anggota_rombel for rombel {$rombel['id']}.\n";
