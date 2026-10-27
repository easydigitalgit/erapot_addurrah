<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 (Intan, Kyanite, Safir, Granit & Topaz) Oktober 2026/LM dan DKN STS B. Inggris Kls 8  Intan, Kyanite, Safir, Granit, Topaz(Oktober 2026).xlsx';

$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($filePath);

$sheet = $spreadsheet->getSheetByName('8 INTAN');
echo "Row 10 (Raw Value):\n";
echo "  C10: " . $sheet->getCell('C10')->getValue() . "\n";
echo "  D10: " . $sheet->getCell('D10')->getValue() . "\n";
echo "  H10: " . $sheet->getCell('H10')->getValue() . "\n";
echo "  M10: " . $sheet->getCell('M10')->getValue() . "\n";

echo "Row 10 (Calculated/Cached):\n";
try {
    echo "  C10: " . $sheet->getCell('C10')->getCalculatedValue() . "\n";
    echo "  M10: " . $sheet->getCell('M10')->getCalculatedValue() . "\n";
} catch (Exception $e) {
    echo "  Calculation failed: " . $e->getMessage() . "\n";
    echo "  OldCalculatedValue C10: " . $sheet->getCell('C10')->getOldCalculatedValue() . "\n";
    echo "  OldCalculatedValue M10: " . $sheet->getCell('M10')->getOldCalculatedValue() . "\n";
}

