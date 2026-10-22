<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

foreach ($spreadsheet->getSheetNames() as $sName) {
    $sheet = $spreadsheet->getSheetByName($sName);
    $highestRow = $sheet->getHighestDataRow();
    for ($r = 1; $r <= $highestRow; $r++) {
        $nama = (string)$sheet->getCell('B' . $r)->getValue();
        if (stripos($nama, 'IQBAL') !== false || stripos($nama, 'NAUFAL') !== false) {
            echo "Sheet [$sName] Row $r: $nama (NIS: " . $sheet->getCell('C' . $r)->getValue() . ", Rombel: " . $sheet->getCell('AH' . $r)->getValue() . ")\n";
        }
    }
}
