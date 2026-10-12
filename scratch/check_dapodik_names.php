<?php
require_once __DIR__ . '/../backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = __DIR__ . '/../docs/Data Siswa Dapodik Perkelas.xlsx';
if (file_exists($file)) {
    $reader = IOFactory::createReaderForFile($file);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($file);
    foreach ($spreadsheet->getSheetNames() as $sheetName) {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $data = $sheet->toArray();
        foreach ($data as $rIdx => $row) {
            foreach ($row as $cIdx => $cell) {
                if (stripos((string)$cell, 'Buqhori') !== false || stripos((string)$cell, 'Alvaro') !== false || stripos((string)$cell, 'Bahri') !== false) {
                    echo "Found in sheet '$sheetName', Row $rIdx, Col $cIdx: $cell\n";
                    echo "Row sample: " . implode(" | ", array_slice(array_filter($row), 0, 10)) . "\n\n";
                    break 2;
                }
            }
        }
    }
} else {
    echo "File not found.\n";
}
