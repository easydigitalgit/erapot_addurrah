<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$counts = [];
for ($r = 2; $r <= $sheet->getHighestRow(); $r++) {
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());
    if ($kelas) {
        if (!isset($counts[$kelas])) $counts[$kelas] = 0;
        $counts[$kelas]++;
    }
}
print_r($counts);

