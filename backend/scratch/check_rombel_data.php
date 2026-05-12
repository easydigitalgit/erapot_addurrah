<?php
require 'vendor/autoload.php';
// Define constant to prevent CI4 from complaining
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$loader = \Config\Services::autoloader();
$loader->initialize(new \Config\Autoload(), new \Config\Modules());

$db = \Config\Database::connect();

$ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
echo "TA AKTIF: " . $ta['tahun'] . " " . $ta['semester'] . " (ID: " . $ta['id'] . ")\n";

$rombel = $db->table('rombel')->where('id', 7)->get()->getRowArray();
if ($rombel) {
    echo "ROMBEL ID 7:\n";
    echo " - Nama: " . $rombel['nama_rombel'] . "\n";
    echo " - Tingkat: " . $rombel['tingkat'] . "\n";
    echo " - Wali Kelas ID: " . $rombel['wali_kelas_id'] . "\n";
    echo " - TA ID: " . $rombel['id_tahun_ajaran'] . "\n";
} else {
    echo "ROMBEL ID 7 TIDAK DITEMUKAN!\n";
}

$wali = $db->table('guru_tendik')->where('id', 41)->get()->getRowArray();
if ($wali) {
    echo "WALI KELAS ID 41: " . $wali['nama_lengkap'] . "\n";
} else {
    echo "WALI KELAS ID 41 TIDAK DITEMUKAN!\n";
}
