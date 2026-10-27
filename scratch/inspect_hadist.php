<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN Sumatif Tengah Semester.xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(false);
$spreadsheet = $reader->load($filePath);

foreach ($spreadsheet->getSheetNames() as $sName) {
    $sheet = $spreadsheet->getSheetByName($sName);
    echo "\n=== Sheet: $sName ===\n";
    for ($r = 8; $r <= 12; $r++) {
        $rowVals = [];
        for ($c = 'A'; $c <= 'N'; $c++) {
            $v = $sheet->getCell($c . $r)->getCalculatedValue();
            if ($v !== null && $v !== '') $rowVals[] = "$c:$v";
        }
        echo "Row $r: " . implode(" | ", $rowVals) . "\n";
    }
}

