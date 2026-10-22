<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== ANGGOTA ROMBEL SAFIR 20 (TA 9) ===\n";
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM anggota_rombel WHERE rombel_id = 20");
echo "Total di rombel 20: " . $res->fetch_assoc()['cnt'] . "\n";

echo "=== ANGGOTA ROMBEL SAFIR 76 (TA 10) ===\n";
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM anggota_rombel WHERE rombel_id = 76");
echo "Total di rombel 76: " . $res->fetch_assoc()['cnt'] . "\n";

echo "=== SISWA DENGAN rombel_id = 76 DI TABEL SISWA ===\n";
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM siswa WHERE rombel_id = 76");
echo "Total siswa where rombel_id = 76: " . $res->fetch_assoc()['cnt'] . "\n";

echo "=== SISWA DENGAN rombel_id = 20 DI TABEL SISWA ===\n";
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM siswa WHERE rombel_id = 20");
echo "Total siswa where rombel_id = 20: " . $res->fetch_assoc()['cnt'] . "\n";

echo "\n=== CEK SISWA DI DAPODIK SHEET VIII SAFIR ===\n";
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheet = $spreadsheet->getSheetByName('VIII');
if ($sheet) {
    $rows = $sheet->toArray(null, true, true, true);
    $safirStudents = [];
    foreach ($rows as $r) {
        $rombel = trim($r['AH'] ?? '');
        if (stripos($rombel, 'Safir') !== false) {
            $safirStudents[] = ($r['C'] ?? '') . ' | ' . ($r['B'] ?? '');
        }
    }
    echo "Total siswa Safir di Sheet VIII Dapodik: " . count($safirStudents) . "\n";
    foreach ($safirStudents as $s) echo "- $s\n";
}
