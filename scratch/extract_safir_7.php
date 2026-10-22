<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheet = $spreadsheet->getSheetByName('VIII');
$rows = $sheet->toArray(null, true, true, true);

$missingNames = [
    "Alifa Nur Zahirah",
    "ALYA JAZILA ARANI",
    "Aqilah Khanza Ramadhani",
    "EARLYTA ARSYFA",
    "Inaya Athifa",
    "SALWA TSABITA",
    "SYIFA NAURA AFAF"
];

echo "=== DETAIL DATA 7 SISWA SAFIR DI DAPODIK ===\n";
foreach ($rows as $idx => $r) {
    $nama = trim($r['B'] ?? '');
    foreach ($missingNames as $mn) {
        if (stripos($nama, $mn) !== false) {
            echo "Row $idx:\n";
            echo "  Nama: " . $nama . "\n";
            echo "  NIPD/NIS: " . ($r['C'] ?? '') . "\n";
            echo "  NISN: " . ($r['D'] ?? '') . "\n";
            echo "  NIK: " . ($r['G'] ?? '') . "\n";
            echo "  Tempat Lahir: " . ($r['E'] ?? '') . "\n";
            echo "  Tgl Lahir: " . ($r['F'] ?? '') . "\n";
            echo "  Alamat: " . ($r['H'] ?? '') . "\n";
            echo "  Kelurahan: " . ($r['J'] ?? '') . "\n";
            echo "  Kecamatan: " . ($r['K'] ?? '') . "\n";
            echo "  HP: " . ($r['O'] ?? '') . "\n";
            echo "  Ayah: " . ($r['P'] ?? '') . "\n";
            echo "  Ibu: " . ($r['V'] ?? '') . "\n";
        }
    }
}
