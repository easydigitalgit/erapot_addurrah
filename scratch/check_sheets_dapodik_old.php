<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas.xlsx');
echo "Sheets in Data Siswa Dapodik Perkelas.xlsx:\n";
print_r($spreadsheet->getSheetNames());
