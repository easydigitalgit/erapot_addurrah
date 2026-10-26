<?php
require 'backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== CEK EXCEL Data Siswa Dapodik Perkelas TA 2026-2027.xlsx ===\n";
$excelFile = 'docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx';
if (file_exists($excelFile)) {
    $reader = IOFactory::createReaderForFile($excelFile);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($excelFile);
    $sheetNames = $spreadsheet->getSheetNames();
    echo "Sheets: " . implode(', ', $sheetNames) . "\n";
    foreach ($sheetNames as $sheetName) {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++) {
            $val = $sheet->getCell('B' . $row)->getValue(); // or check all cols
            for ($col = 'A'; $col <= 'Z'; $col++) {
                $cellVal = (string)$sheet->getCell($col . $row)->getValue();
                if (stripos($cellVal, 'FIRZANA') !== false) {
                    echo "Found FIRZANA in sheet '$sheetName', row $row, col $col:\n";
                    // Print row data with header (row 1 or 2 or 5)
                    for ($c = 'A'; $c <= 'AN'; $c++) {
                        $v = $sheet->getCell($c . $row)->getValue();
                        $h = $sheet->getCell($c . '1')->getValue() ?: $sheet->getCell($c . '2')->getValue() ?: $sheet->getCell($c . '3')->getValue() ?: $sheet->getCell($c . '4')->getValue() ?: $sheet->getCell($c . '5')->getValue();
                        if ($v !== null && $v !== '') {
                            echo "  [$c] $h => $v\n";
                        }
                    }
                    break 2;
                }
            }
        }
    }
} else {
    echo "Excel file not found!\n";
}

echo "\n=== CEK Auto_Full_20261012_130712.sql ===\n";
$sqlBackup = 'backend/writable/backups/Auto_Full_20261012_130712.sql';
if (file_exists($sqlBackup)) {
    $handle = fopen($sqlBackup, 'r');
    $in_ow = false;
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `orangtua_wali`") !== false) {
            $in_ow = true;
        }
        if (stripos($line, "FIRZANA") !== false) {
            echo "Found FIRZANA in sql backup:\n" . substr($line, 0, 300) . "\n";
        }
        if ($in_ow && (strpos($line, "442") !== false || stripos($line, "FIRZANA") !== false)) {
            echo "Found in OW sql backup:\n" . substr($line, 0, 300) . "\n";
        }
        if ($in_ow && strpos($line, ";") !== false && strpos($line, "INSERT INTO `orangtua_wali`") === false) {
            $in_ow = false;
        }
    }
    fclose($handle);
}
