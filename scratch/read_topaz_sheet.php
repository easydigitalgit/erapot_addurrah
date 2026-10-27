<?php
require 'backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('8 TOPAZ');

echo "Highest Row: " . $sheet->getHighestRow() . ", Highest Col: " . $sheet->getHighestColumn() . "\n";

for ($r = 7; $r <= 15; $r++) {
    $rowVals = [];
    foreach (range('A', 'Z') as $c) {
        $val = $sheet->getCell($c . $r)->getFormattedValue();
        if ($val !== '' && $val !== null) {
            $rowVals[] = "$c$r: '$val'";
        }
    }
    if (!empty($rowVals)) {
        echo "Row $r: " . implode(' | ', $rowVals) . "\n";
    }
}

