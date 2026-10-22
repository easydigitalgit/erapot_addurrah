<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheet = $spreadsheet->getSheetByName('VIII');
$highestRow = $sheet->getHighestDataRow();

echo "Highest Row in VIII: $highestRow\n";
for ($r = 1; $r <= $highestRow; $r++) {
    $valA = trim((string)$sheet->getCell('A' . $r)->getValue());
    $valB = trim((string)$sheet->getCell('B' . $r)->getValue());
    $valAH = trim((string)$sheet->getCell('AH' . $r)->getValue());
    if (stripos($valA, 'safir') !== false || stripos($valAH, 'safir') !== false || stripos($valB, 'safir') !== false) {
        echo "Row $r: A=$valA, B=$valB, AH=$valAH\n";
    }
}
