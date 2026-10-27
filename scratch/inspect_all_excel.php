<?php
require 'backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$dir = 'docs/data_nilai_sts_ganjil_2026';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$excelFiles = [];
foreach ($iterator as $file) {
    if ($file->isFile() && in_array(strtolower($file->getExtension()), ['xlsx', 'xls'])) {
        $excelFiles[] = $file->getPathname();
    }
}

sort($excelFiles);
echo "Ditemukan " . count($excelFiles) . " file Excel:\n\n";

$summary = [];

foreach ($excelFiles as $filePath) {
    $relPath = str_replace('\\', '/', $filePath);
    $baseName = basename($filePath);
    try {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $sheetNames = $reader->listWorksheetNames($filePath);
        
        $summary[$baseName] = [
            'path' => $relPath,
            'sheets' => $sheetNames
        ];
        
        echo "========================================================\n";
        echo "FILE: $baseName\n";
        echo "Sheets (" . count($sheetNames) . "): " . implode(', ', $sheetNames) . "\n";
        
        // Baca sheet pertama
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        
        echo "First Sheet: '{$sheetNames[0]}' ($highestRow rows x $highestCol cols)\n";
        
        // Print 5 baris pertama yang ada isinya
        $nonEmptyCount = 0;
        for ($r = 1; $r <= min(15, $highestRow); $r++) {
            $rowVals = [];
            for ($c = 'A'; $c <= min('M', $highestCol); $c++) {
                $val = $sheet->getCell($c . $r)->getValue();
                if ($val !== null && $val !== '') {
                    $rowVals[] = "$c:$val";
                }
            }
            if ($rowVals) {
                echo "  Row $r: " . implode(" | ", array_slice($rowVals, 0, 8)) . "\n";
                $nonEmptyCount++;
            }
        }
        echo "\n";
    } catch (Exception $e) {
        echo "ERROR reading $baseName: " . $e->getMessage() . "\n\n";
    }
}

