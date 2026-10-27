<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$names = ['Fahrian Haikal', 'FAEYZA MOUZA', 'ISBAH FAJAR', 'Jahira Humairoh', 'Naufal Zidan', 'NAURA DZAKIYYA', 'DIMAS ALVINO', 'EARLYTA', 'Ghaisan Agha', 'Inaya Athifa'];

for ($r = 2; $r <= $sheet->getHighestRow(); $r++) {
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    $nis = trim((string)$sheet->getCell('C' . $r)->getFormattedValue());
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());

    foreach ($names as $n) {
        if (stripos($nama, $n) !== false) {
            echo "Row $r: Nama = '$nama' | NIS = '$nis' | NISN = '$nisn' | Kelas = '$kelas'\n";
        }
    }
}

