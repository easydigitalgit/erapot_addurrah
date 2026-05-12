<?php
require 'vendor/autoload.php';
// Define constant to prevent CI4 from complaining
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$loader = \Config\Services::autoloader();
$loader->initialize(new \Config\Autoload(), new \Config\Modules());

$db = \Config\Database::connect();

echo "--- STRUCTURE ROMBEL ---\n";
$fields = $db->getFieldNames('rombel');
print_r($fields);

echo "\n--- DATA ROMBEL ZAMRUD (ID 7) ---\n";
$rombel = $db->table('rombel')->where('id', 7)->get()->getRowArray();
print_r($rombel);

$ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
echo "\nTA AKTIF ID: " . $ta['id'] . " (" . $ta['tahun'] . " " . $ta['semester'] . ")\n";

$f_tingkat = "IX";
$f_rombel = 7;
$f_wali = 41;

$builder = $db->table('rombel r')
    ->where('r.id_tahun_ajaran', $ta['id']);

if ($f_tingkat) $builder->where('r.tingkat', $f_tingkat);
if ($f_rombel)  $builder->where('r.id', $f_rombel);
if ($f_wali)    $builder->where('r.wali_kelas_id', $f_wali);

echo "\n--- TEST QUERY ---\n";
echo $builder->getCompiledSelect() . "\n";
$res = $builder->get()->getResultArray();
echo "RESULT COUNT: " . count($res) . "\n";
if (count($res) > 0) {
    print_r($res[0]);
} else {
    echo "NO DATA FOUND WITH FILTERS\n";
    // Check which filter failed
    echo "\nChecking Individual Filters:\n";
    echo "Is TA ID matching? " . ($rombel['id_tahun_ajaran'] == $ta['id'] ? "YES" : "NO") . "\n";
    echo "Is Tingkat matching? " . ($rombel['tingkat'] == $f_tingkat ? "YES" : "NO") . "\n";
    echo "Is Wali matching? " . ($rombel['wali_kelas_id'] == $f_wali ? "YES" : "NO") . "\n";
}
