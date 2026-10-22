<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');
preg_match_all("/\((\d+),\s*\d+,\s*'[^']+',\s*'([^']+)'/", $sql, $matches, PREG_SET_ORDER);

echo "Checking " . count($matches) . " students for duplicate NISN in DB...\n";
foreach ($matches as $mRow) {
    $id = $mRow[1];
    $nisn = $mRow[2];
    $dup = $m->query("SELECT id, nis, nama_lengkap FROM siswa WHERE nisn = '$nisn' AND id != $id")->fetch_all(MYSQLI_ASSOC);
    if (!empty($dup)) {
        echo "⚠️ DUPLICATE NISN: $nisn for ID $id! Also used by:\n";
        print_r($dup);
    }
}
echo "Done checking duplicate NISN.\n";
