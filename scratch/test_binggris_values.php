<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 (Intan, Kyanite, Safir, Granit & Topaz) Oktober 2026/LM dan DKN STS B. Inggris Kls 8  Intan, Kyanite, Safir, Granit, Topaz(Oktober 2026).xlsx';

$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($filePath);

foreach (['8 INTAN', '8 KYANITE', '8 SAFIR', '8 GRANIT', '8 TOPAZ'] as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    echo "\n=== $sheetName ===\n";
    for ($r = 10; $r <= 13; $r++) {
        $no   = $sheet->getCell("A$r")->getValue();
        $nama = $sheet->getCell("B$r")->getValue();
        $nh1  = $sheet->getCell("C$r")->getCalculatedValue();
        $nh2  = $sheet->getCell("D$r")->getCalculatedValue();
        $uh1  = $sheet->getCell("H$r")->getCalculatedValue();
        $uh2  = $sheet->getCell("I$r")->getCalculatedValue();
        $pts  = $sheet->getCell("M$r")->getCalculatedValue();
        echo "[$no] $nama | NH1:$nh1, NH2:$nh2 | UH1:$uh1, UH2:$uh2 | PTS:$pts\n";
    }
}

