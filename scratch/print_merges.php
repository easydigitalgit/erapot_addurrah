<?php
require 'backend/vendor/autoload.php';

$file = 'docs/Template_Kolektif_Tengah_Amazonite.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

echo "=== MERGED CELLS ===\n";
foreach ($sheet->getMergeCells() as $merge) {
    echo "$merge\n";
}
