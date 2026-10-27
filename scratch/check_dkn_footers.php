<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

foreach (['DKN STS Kyanite.xlsx', 'DKN STS Safir.xlsx', 'DKN Sumatif Tengah Semester Ganjil (Safir).xlsx'] as $fName) {
    $path = "docs/data_nilai_sts_ganjil_2026/$fName";
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($path);
    $sheet = $spreadsheet->getActiveSheet();
    $maxR = $sheet->getHighestDataRow();
    echo "=== $fName (Max Row: $maxR) ===\n";
    for ($r = max(1, $maxR - 10); $r <= $maxR; $r++) {
        $rowVals = [];
        for ($c = 'A'; $c <= 'N'; $c++) {
            $v = $sheet->getCell($c . $r)->getValue();
            if ($v !== null && $v !== '') $rowVals[] = "$c:$v";
        }
        if ($rowVals) echo "Row $r: " . implode(" | ", $rowVals) . "\n";
    }
}

