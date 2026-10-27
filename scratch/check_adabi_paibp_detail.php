<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('8 TOPAZ');

echo "Row 10 for Adabi Limansyah Sagala:\n";
foreach (range('A', 'Q') as $col) {
    $header = $sheet->getCell($col . '9')->getFormattedValue();
    $val = $sheet->getCell($col . '10')->getFormattedValue();
    echo "$col (H: '$header'): '$val'\n";
}

