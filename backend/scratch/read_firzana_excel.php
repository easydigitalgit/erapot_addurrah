<?php
require 'backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$excelFile = 'docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx';
$reader = IOFactory::createReaderForFile($excelFile);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($excelFile);

$sheet = $spreadsheet->getSheetByName('DAFTAR PESERTA DIDIK');
for ($r = 1; $r <= 6; $r++) {
    echo "Row $r: ";
    for ($c = 1; $c <= 40; $c++) {
        $val = $sheet->getCell([$c, $r])->getValue();
        if ($val) echo "[$c: $val] ";
    }
    echo "\n";
}

echo "\n--- FIRZANA IN EXCEL ---\n";
for ($r = 1; $r <= $sheet->getHighestRow(); $r++) {
    $nama = $sheet->getCell([2, $r])->getValue();
    if (stripos($nama, 'FIRZANA') !== false) {
        echo "Row $r:\n";
        for ($c = 1; $c <= 45; $c++) {
            $header = $sheet->getCell([$c, 4])->getValue() ?: $sheet->getCell([$c, 5])->getValue() ?: $sheet->getCell([$c, 6])->getValue();
            $val = $sheet->getCell([$c, $r])->getValue();
            echo "Col $c ($header) = $val\n";
        }
    }
}
