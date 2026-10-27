<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Topaz).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

echo "Sheet Name: " . $sheet->getTitle() . "\n";
for ($r = 8; $r <= 15; $r++) {
    $rowVals = [];
    foreach (range('A', 'P') as $c) {
        $val = $sheet->getCell($c . $r)->getFormattedValue();
        if ($val !== '' && $val !== null) {
            $rowVals[] = "$c$r: '$val'";
        }
    }
    if (!empty($rowVals)) {
        echo "Row $r: " . implode(' | ', $rowVals) . "\n";
    }
}

