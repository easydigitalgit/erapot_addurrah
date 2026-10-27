<?php
require 'backend/vendor/autoload.php';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile('docs/data_nilai_sts_ganjil_2026/LM STS Kelas 8 T.A. 2026-2027.xlsx');
$spreadsheet = $reader->load('docs/data_nilai_sts_ganjil_2026/LM STS Kelas 8 T.A. 2026-2027.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

foreach ($rows as $i => $row) {
    echo "Row $i: " . implode(" | ", array_filter($row, fn($x) => $x !== null && $x !== '')) . "\n";
}

