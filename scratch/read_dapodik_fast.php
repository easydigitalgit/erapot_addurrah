<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../docs/Data Siswa Dapodik Perkelas.xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(true);

echo "=== DAFTAR SHEET DALAM FILE EXCEL ===\n";
$sheetNames = $reader->listWorksheetNames($filePath);
print_r($sheetNames);

// Cari sheet yang berhubungan dengan Carnelian atau Kelas 7
$targetSheet = null;
foreach ($sheetNames as $name) {
    if (stripos($name, 'Carnelian') !== false || stripos($name, '7') !== false || stripos($name, 'VII') !== false) {
        echo "Found potential sheet: $name\n";
        if (!$targetSheet) $targetSheet = $name;
    }
}

if (!$targetSheet && !empty($sheetNames)) {
    $targetSheet = $sheetNames[0];
}

echo "\nMembaca sheet: $targetSheet\n";
$reader->setLoadSheetsOnly([$targetSheet]);
$spreadsheet = $reader->load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray(null, true, true, true);

echo "Total baris: " . count($data) . "\n";
echo "Header Baris 1:\n";
print_r(array_filter($data[1] ?? []));

echo "\nContoh Baris 2:\n";
print_r(array_filter($data[2] ?? []));

echo "\nCari 7 siswa di sheet ini:\n";
$targetNames = [
    'Ariqa', 'Ashika', 'Ataya', 'Jihan Talita', 'Keisha Amila', 'Nadifa', 'Qabsha'
];

foreach ($data as $idx => $row) {
    $rowStr = implode(' | ', $row);
    foreach ($targetNames as $tn) {
        if (stripos($rowStr, $tn) !== false) {
            echo "Baris $idx [Found $tn]:\n";
            // Tampilkan kolom A, B, C, D, E, AQ, I, dsb.
            foreach ($row as $colK => $val) {
                if (!empty($val)) echo "   $colK => $val\n";
            }
        }
    }
}
