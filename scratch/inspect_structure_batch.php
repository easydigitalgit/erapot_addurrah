<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$dir = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027';

$checkFiles = [
    'Bahasa Indonesia (8 Topaz).xlsx',
    'Bahasa Indonesia (8 Granit, 8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'PAIBP (8 Granit).xlsx',
    'PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'Matematika (8 Topaz, 8 Safir).xlsx',
    'PKN (Seluruh Kelas 8).xlsx',
    'IPS (Seluruh Kelas 8).xlsx',
    'PJOK (8 Granit & 8 Topaz).xlsx',
    'PJOK (8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'Seni Budaya (8 Granit, 8 Kyanite, 8 Safir).xlsx',
    'Informatika (Seluruh Kelas 8).xlsx'
];

foreach ($checkFiles as $fName) {
    $fPath = "$dir/$fName";
    if (!file_exists($fPath)) continue;
    $reader = IOFactory::createReaderForFile($fPath);
    $reader->setReadDataOnly(true);
    $sNames = $reader->listWorksheetNames($fPath);
    $ss = $reader->load($fPath);
    echo "=========================================================\n";
    echo "FILE: $fName (Sheets: " . implode(', ', $sNames) . ")\n";
    $sh = $ss->getSheet(0);
    echo "  Row 8: A=" . $sh->getCell('A8')->getValue() . " | B=" . $sh->getCell('B8')->getValue() . " | C=" . $sh->getCell('C8')->getValue() . " | D=" . $sh->getCell('D8')->getValue() . " | E=" . $sh->getCell('E8')->getValue() . " | G=" . $sh->getCell('G8')->getValue() . " | H=" . $sh->getCell('H8')->getValue() . " | M=" . $sh->getCell('M8')->getValue() . "\n";
    echo "  Row 9: C=" . $sh->getCell('C9')->getValue() . " | D=" . $sh->getCell('D9')->getValue() . " | E=" . $sh->getCell('E9')->getValue() . " | F=" . $sh->getCell('F9')->getValue() . " | G=" . $sh->getCell('G9')->getValue() . " | H=" . $sh->getCell('H9')->getValue() . " | I=" . $sh->getCell('I9')->getValue() . " | M=" . $sh->getCell('M9')->getValue() . "\n";
    echo "  Sample Row 10: Name=" . $sh->getCell('B10')->getValue() . " | C=" . $sh->getCell('C10')->getCalculatedValue() . " | D=" . $sh->getCell('D10')->getCalculatedValue() . " | E=" . $sh->getCell('E10')->getCalculatedValue() . " | H=" . $sh->getCell('H10')->getCalculatedValue() . " | I=" . $sh->getCell('I10')->getCalculatedValue() . " | G=" . $sh->getCell('G10')->getCalculatedValue() . " | M=" . $sh->getCell('M10')->getCalculatedValue() . "\n";
}

