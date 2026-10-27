<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

foreach (['DKN STS Kyanite.xlsx', 'DKN STS Safir.xlsx', 'DKN Sumatif Tengah Semester Ganjil (Safir).xlsx', 'DKN Sumatif Tengah Semester safir.xlsx'] as $fName) {
    $path = "docs/data_nilai_sts_ganjil_2026/$fName";
    if (!file_exists($path)) continue;
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($path);
    $sheet = $spreadsheet->getActiveSheet();
    echo "=== $fName ===\n";
    for ($r = 1; $r <= 7; $r++) {
        $rowVals = [];
        for ($c = 'A'; $c <= 'K'; $c++) {
            $v = $sheet->getCell($c . $r)->getValue();
            if ($v !== null && $v !== '') $rowVals[] = "$c:$v";
        }
        if ($rowVals) echo "Row $r: " . implode(" | ", $rowVals) . "\n";
    }
}

