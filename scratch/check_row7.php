<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Template_Kolektif_Tengah_Safir.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$highestCol = $sheet->getHighestDataColumn();
$highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

echo "Row 7 contents in docs/Template_Kolektif_Tengah_Safir.xlsx:\n";
for ($c = 1; $c <= $highestColIndex; $c++) {
    $colLtr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $v7 = $sheet->getCell($colLtr . '7')->getValue();
    $v8 = $sheet->getCell($colLtr . '8')->getValue();
    $v9 = $sheet->getCell($colLtr . '9')->getValue();
    echo "Col $colLtr: Row7='$v7' | Row8='$v8' | Row9='$v9'\n";
}
