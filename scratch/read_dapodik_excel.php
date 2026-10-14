<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../docs/Data Siswa Dapodik Perkelas.xlsx';
if (!file_exists($filePath)) {
    die("File not found: $filePath\n");
}

$spreadsheet = IOFactory::load($filePath);
echo "=== SHEET LIST ===\n";
foreach ($spreadsheet->getSheetNames() as $sheetName) {
    echo "- Sheet: $sheetName\n";
}

// Cari sheet Carnelian atau telusuri semua sheet yang ada siswa Ariqa
foreach ($spreadsheet->getAllSheets() as $sheet) {
    $sheetName = $sheet->getTitle();
    $data = $sheet->toArray(null, true, true, true);
    
    foreach ($data as $rIdx => $row) {
        $nama = trim($row['B'] ?? ($row['D'] ?? ''));
        if (stripos($nama, 'Ariqa') !== false || stripos($nama, 'Carnelian') !== false || stripos($sheetName, 'Carnelian') !== false) {
            echo "\n[Sheet: $sheetName, Row: $rIdx]\n";
            // Tampilkan beberapa kolom penting
            foreach (['A', 'B', 'C', 'D', 'E', 'AQ', 'I'] as $col) {
                if (isset($row[$col])) echo "  Col $col: " . trim($row[$col]) . "\n";
            }
            if (stripos($nama, 'Ariqa') !== false) {
                echo "  -> DITEMUKAN ARIQA DI SINI!\n";
            }
        }
    }
}
