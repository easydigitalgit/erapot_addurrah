<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('8 TOPAZ');

for ($r = 10; $r <= 45; $r++) {
    $no = $sheet->getCell('A' . $r)->getFormattedValue();
    $nama = trim($sheet->getCell('B' . $r)->getFormattedValue());
    if ($nama) {
        echo "Row $r: No $no - $nama\n";
    }
}

