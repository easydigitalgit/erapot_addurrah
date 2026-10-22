<?php
require 'backend/vendor/autoload.php';

$file = 'docs/Template_Kolektif_Tengah_Amazonite.xlsx';
if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestDataRow();
$highestCol = $sheet->getHighestColumn();

echo "=== FILE INFO ===\n";
echo "Highest Row: $highestRow, Highest Col: $highestCol\n";
echo "Title A1: " . $sheet->getCell('A1')->getValue() . "\n";
echo "A2: " . $sheet->getCell('A2')->getValue() . "\n";
echo "Kelas (C4): " . $sheet->getCell('C4')->getValue() . "\n";
echo "Mapel (C5): " . $sheet->getCell('C5')->getValue() . "\n";
echo "Jenis (C6): " . $sheet->getCell('C6')->getValue() . "\n";
echo "Hidden A7 (ta_id): " . $sheet->getCell('A7')->getValue() . "\n";
echo "Hidden B7 (rombel_id): " . $sheet->getCell('B7')->getValue() . "\n";
echo "Hidden C7 (mapel_id): " . $sheet->getCell('C7')->getValue() . "\n";

echo "\n=== COLUMNS (Row 7, 8, 9) ===\n";
for ($col = 'A'; $col <= $highestCol; $col++) {
    $r7 = $sheet->getCell($col . '7')->getValue();
    $r8 = $sheet->getCell($col . '8')->getValue();
    $r9 = $sheet->getCell($col . '9')->getValue();
    echo "Col $col: [Row7: $r7] [Row8: $r8] [Row9: $r9]\n";
}

echo "\n=== DATA ROWS (Row 10 to $highestRow) ===\n";
for ($r = 10; $r <= $highestRow; $r++) {
    $no = $sheet->getCell('A' . $r)->getValue();
    $nis = $sheet->getCell('B' . $r)->getValue();
    $nama = $sheet->getCell('C' . $r)->getValue();
    
    $vals = [];
    for ($col = 'D'; $col <= $highestCol; $col++) {
        $calcVal = $sheet->getCell($col . $r)->getCalculatedValue();
        $rawVal = $sheet->getCell($col . $r)->getValue();
        if ($calcVal !== $rawVal) {
            $vals[] = "$col: $rawVal (calc: $calcVal)";
        } else {
            $vals[] = "$col: $rawVal";
        }
    }
    echo sprintf("Row %2d | No: %-2s | NIS: %-12s | Nama: %-30s | %s\n", $r, $no, $nis, substr($nama, 0, 30), implode(', ', $vals));
}
