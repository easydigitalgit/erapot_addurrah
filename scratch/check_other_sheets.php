<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);

foreach (['8 INTAN', '8 KYANITE', '8 SAFIR'] as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    echo "=== $sheetName ===\n";
    for ($c = 'M'; $c <= 'R'; $c++) {
        echo "$c8: " . $sheet->getCell($c . '8')->getFormattedValue() . " | $c9: " . $sheet->getCell($c . '9')->getFormattedValue() . " | $c10: " . $sheet->getCell($c . '10')->getFormattedValue() . "\n";
    }
}

