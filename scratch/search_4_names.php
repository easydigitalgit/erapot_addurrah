<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

$names = ['ARJUSE', 'ATHALLAH', 'MALAIKA', 'RIFKI'];

foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestDataRow();
    for ($r = 1; $r <= $highestRow; $r++) {
        $valB = (string)$sheet->getCell('B' . $r)->getValue();
        foreach ($names as $n) {
            if (stripos($valB, $n) !== false) {
                echo "Found in Sheet [$sheetName] Row $r: B={$valB}, C=" . $sheet->getCell('C' . $r)->getValue() . ", D=" . $sheet->getCell('D' . $r)->getValue() . ", AH=" . $sheet->getCell('AH' . $r)->getValue() . "\n";
            }
        }
    }
}
