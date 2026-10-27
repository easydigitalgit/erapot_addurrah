<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('8 TOPAZ');

echo "Formula in N10 (Rata NH) : " . $sheet->getCell('N10')->getValue() . " -> Calculated: " . $sheet->getCell('N10')->getCalculatedValue() . "\n";
echo "Formula in O10 (Rata UH) : " . $sheet->getCell('O10')->getValue() . " -> Calculated: " . $sheet->getCell('O10')->getCalculatedValue() . "\n";
echo "Formula in P10 (NR)      : " . $sheet->getCell('P10')->getValue() . " -> Calculated: " . $sheet->getCell('P10')->getCalculatedValue() . "\n";

