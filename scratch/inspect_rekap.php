<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheetRekap = $spreadsheet->getSheetByName('REKAP JUMLAH SISWA');

echo "=== REKAP JUMLAH SISWA ===\n";
$highestRow = $sheetRekap->getHighestDataRow();
for ($r = 1; $r <= $highestRow; $r++) {
    $rowStr = [];
    for ($c = 'A'; $c <= 'G'; $c++) {
        $val = trim((string)$sheetRekap->getCell($c . $r)->getValue());
        if ($val !== '') $rowStr[] = "$c:$val";
    }
    if (!empty($rowStr)) {
        echo "Row $r: " . implode(' | ', $rowStr) . "\n";
    }
}
