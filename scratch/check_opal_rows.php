<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheet = $spreadsheet->getSheetByName('VII');

echo "=== OPAL IN DAPODIK (ROWS 35 TO 65) ===\n";
for ($r = 35; $r <= 65; $r++) {
    $a = $sheet->getCell('A' . $r)->getValue();
    $b = $sheet->getCell('B' . $r)->getValue();
    $c = $sheet->getCell('C' . $r)->getValue();
    $d = $sheet->getCell('D' . $r)->getValue();
    if ($a || $b) {
        echo "Row $r: A=$a | B=$b | C=$c | D=$d\n";
    }
}
