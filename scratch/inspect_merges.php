<?php
require 'backend/vendor/autoload.php';

$file = 'docs/Template_Kolektif_Tengah_Amazonite.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

echo "=== MERGED CELLS ===\n";
foreach ($sheet->getMergeCells() as $merge) {
    echo "$merge\n";
}

echo "\n=== ROW 7 DETAILS ===\n";
for ($c = 'A'; $c <= 'I'; $c++) {
    $cell = $sheet->getCell($c . '7');
    echo "Cell $c 7: val='" . $cell->getValue() . "' style=" . json_encode($sheet->getStyle($c . '7')->exportArray()) . "\n";
}

echo "\n=== ROW 8 & 9 DETAILS ===\n";
for ($c = 'A'; $c <= 'I'; $c++) {
    $c8 = $sheet->getCell($c . '8')->getValue();
    $c9 = $sheet->getCell($c . '9')->getValue();
    echo "Col $c: Row8='$c8', Row9='$c9'\n";
}
