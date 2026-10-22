<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Template_Kolektif_Tengah_Safir.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestDataRow();
$highestCol = $sheet->getHighestColumn();

echo "Highest Row: $highestRow, Highest Col: $highestCol\n";
for ($c = 'A'; $c <= $highestCol; $c++) {
    $code = $sheet->getCell($c . '7')->getValue();
    $hdr = $sheet->getCell($c . '8')->getValue();
    if ($code || $hdr) {
        echo "Col $c: code=[$code], header=[$hdr]\n";
    }
}

echo "\n--- Data Rows ---\n";
for ($r = 10; $r <= 31; $r++) {
    $nis = $sheet->getCell('B' . $r)->getValue();
    $nama = $sheet->getCell('C' . $r)->getValue();
    $d = $sheet->getCell('D' . $r)->getValue();
    $e = $sheet->getCell('E' . $r)->getValue();
    $f = $sheet->getCell('F' . $r)->getValue();
    $g = $sheet->getCell('G' . $r)->getValue();
    echo "Row $r: NIS=$nis | Nama=$nama | D=$d | E=$e | F=$f | G=$g\n";
}
