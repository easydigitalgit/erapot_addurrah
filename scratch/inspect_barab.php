<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== INSPEKSI DKN BAHASA ARAB VIII STS Ganjil.xlsx ===\n";
$reader = IOFactory::createReaderForFile('docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx');
$reader->setReadDataOnly(false); // calculate formulas
$spreadsheet = $reader->load('docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx');

foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestDataRow();
    echo "\nSheet: $sheetName (Max Row: $highestRow)\n";
    
    // Header
    echo "Row 8: " . $sheet->getCell('A8')->getValue() . " | " . $sheet->getCell('B8')->getValue() . " | " . $sheet->getCell('C8')->getValue() . " | " . $sheet->getCell('E8')->getValue() . " | " . $sheet->getCell('G8')->getValue() . " | " . $sheet->getCell('H8')->getValue() . "\n";
    echo "Row 9: " . $sheet->getCell('C9')->getValue() . " | " . $sheet->getCell('D9')->getValue() . " | " . $sheet->getCell('E9')->getValue() . " | " . $sheet->getCell('F9')->getValue() . " | " . $sheet->getCell('G9')->getValue() . "\n";
    
    // Ambil sampel data siswa baris 10 s.d. 14
    for ($r = 10; $r <= min(14, $highestRow); $r++) {
        $no   = $sheet->getCell("A$r")->getValue();
        $nama = trim((string)$sheet->getCell("B$r")->getValue());
        $nh1  = $sheet->getCell("C$r")->getCalculatedValue();
        $nh2  = $sheet->getCell("D$r")->getCalculatedValue();
        $uh1  = $sheet->getCell("E$r")->getCalculatedValue();
        $uh2  = $sheet->getCell("F$r")->getCalculatedValue();
        $sts  = $sheet->getCell("G$r")->getCalculatedValue();
        $rataNh = $sheet->getCell("H$r")->getCalculatedValue();
        $rataUh = $sheet->getCell("I$r")->getCalculatedValue();
        $nr     = $sheet->getCell("J$r")->getCalculatedValue();
        
        if ($nama !== '') {
            echo "  [$no] $nama | NH1: $nh1, NH2: $nh2 | UH1: $uh1, UH2: $uh2 | STS: $sts | NR: $nr\n";
        }
    }
}

