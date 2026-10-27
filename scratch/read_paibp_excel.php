<?php
require 'backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';

if (!file_exists($filePath)) {
    echo "File not found: $filePath\n";
    exit;
}

$spreadsheet = IOFactory::load($filePath);
$sheetNames = $spreadsheet->getSheetNames();
echo "Sheet names in PAIBP Excel:\n";
print_r($sheetNames);

foreach ($sheetNames as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    echo "\n=== Sheet: $sheetName (Max Row: " . $sheet->getHighestRow() . ", Max Col: " . $sheet->getHighestColumn() . ") ===\n";
    // Tampilkan 15 baris pertama
    for ($row = 1; $row <= min(15, $sheet->getHighestRow()); $row++) {
        $rowVals = [];
        for ($col = 'A'; $col <= min('O', $sheet->getHighestColumn()); $col++) {
            $val = $sheet->getCell($col . $row)->getFormattedValue();
            if ($val !== '' && $val !== null) {
                $rowVals[] = "$col$row: $val";
            }
        }
        if (!empty($rowVals)) {
            echo "Row $row: " . implode(' | ', $rowVals) . "\n";
        }
    }
}
