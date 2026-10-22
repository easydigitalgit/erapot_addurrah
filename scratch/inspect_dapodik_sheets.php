<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheets = $spreadsheet->getSheetNames();
echo "Sheets in file:\n";
print_r($sheets);
