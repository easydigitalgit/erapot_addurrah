<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$files = [
    'DKN STS Kyanite.xlsx' => 'docs/data_nilai_sts_ganjil_2026/DKN STS Kyanite.xlsx',
    'DKN STS Safir.xlsx'   => 'docs/data_nilai_sts_ganjil_2026/DKN STS Safir.xlsx',
    'DKN Sumatif Tengah Semester 26-27 GANJIL.xlsx' => 'docs/data_nilai_sts_ganjil_2026/DKN Sumatif Tengah Semester 26-27 GANJIL.xlsx',
    'DKN Sumatif Tengah Semester.xlsx' => 'docs/data_nilai_sts_ganjil_2026/DKN Sumatif Tengah Semester.xlsx',
];

foreach ($files as $name => $path) {
    echo "=========================================================\n";
    echo "FILE: $name\n";
    echo "=========================================================\n";
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $sheetNames = $reader->listWorksheetNames($path);
    echo "Sheets: " . implode(', ', $sheetNames) . "\n";
    
    $spreadsheet = $reader->load($path);
    foreach ($sheetNames as $sName) {
        $sheet = $spreadsheet->getSheetByName($sName);
        $highestRow = $sheet->getHighestDataRow();
        echo "  Sheet: '$sName' (Rows: $highestRow)\n";
        echo "    Row 4 (Kelas): " . $sheet->getCell('A4')->getValue() . " " . $sheet->getCell('B4')->getValue() . " " . $sheet->getCell('C4')->getValue() . "\n";
        echo "    Row 5 (Mapel): " . $sheet->getCell('A5')->getValue() . " " . $sheet->getCell('B5')->getValue() . " " . $sheet->getCell('C5')->getValue() . "\n";
        echo "    Row 8: A=" . $sheet->getCell('A8')->getValue() . " | B=" . $sheet->getCell('B8')->getValue() . " | C=" . $sheet->getCell('C8')->getValue() . " | E=" . $sheet->getCell('E8')->getValue() . " | G=" . $sheet->getCell('G8')->getValue() . "\n";
        echo "    Row 9: C=" . $sheet->getCell('C9')->getValue() . " | D=" . $sheet->getCell('D9')->getValue() . " | E=" . $sheet->getCell('E9')->getValue() . " | G=" . $sheet->getCell('G9')->getValue() . "\n";
        for ($r = 10; $r <= 11; $r++) {
            $no   = $sheet->getCell("A$r")->getValue();
            $nama = $sheet->getCell("B$r")->getValue();
            $c    = $sheet->getCell("C$r")->getCalculatedValue();
            $d    = $sheet->getCell("D$r")->getCalculatedValue();
            $g    = $sheet->getCell("G$r")->getCalculatedValue();
            echo "      Row $r: [$no] $nama | C:$c, D:$d, G:$g\n";
        }
    }
}

