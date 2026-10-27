<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== INSPEKSI DKN NAHWU VIII STS Ganjil.xlsx ===\n";
$reader = IOFactory::createReaderForFile('docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx');
$reader->setReadDataOnly(false);
$spreadsheet = $reader->load('docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx');

foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestDataRow();
    echo "\nSheet: $sheetName (Max Row: $highestRow)\n";
    
    for ($r = 10; $r <= min(14, $highestRow); $r++) {
        $no   = $sheet->getCell("A$r")->getValue();
        $nama = trim((string)$sheet->getCell("B$r")->getValue());
        $nh1  = $sheet->getCell("C$r")->getCalculatedValue();
        $nh2  = $sheet->getCell("D$r")->getCalculatedValue();
        $uh1  = $sheet->getCell("E$r")->getCalculatedValue();
        $uh2  = $sheet->getCell("F$r")->getCalculatedValue();
        $sts  = $sheet->getCell("G$r")->getCalculatedValue();
        $nr     = $sheet->getCell("J$r")->getCalculatedValue();
        
        if ($nama !== '') {
            echo "  [$no] $nama | NH1: $nh1, NH2: $nh2 | UH1: $uh1, UH2: $uh2 | STS: $sts | NR: $nr\n";
        }
    }
}

