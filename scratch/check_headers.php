<?php
require_once __DIR__ . '/../backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = __DIR__ . '/test_sample_import.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$headers = $sheet->rangeToArray('A1:U1')[0];

foreach ($headers as $colIdx => $h) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
    echo "Column $colLetter ($colIdx): '$h'\n";
}
