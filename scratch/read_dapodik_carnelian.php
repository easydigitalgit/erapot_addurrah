<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../docs/Data Siswa Dapodik Perkelas.xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(true);
$reader->setLoadSheetsOnly(['Daftar Peserta Didik']);
$spreadsheet = $reader->load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$carnelianRows = [];

foreach ($sheet->getRowIterator() as $row) {
    $rIdx = $row->getRowIndex();
    if ($rIdx > 600) break;
    
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    $cells = [];
    foreach ($cellIterator as $cell) {
        $cells[$cell->getColumn()] = $cell->getValue();
    }
    
    $nama = trim((string)($cells['B'] ?? ''));
    $rombel = trim((string)($cells['AQ'] ?? ''));
    
    if (empty($nama) && empty($rombel)) continue;
    
    if (stripos($rombel, 'Carnelian') !== false || stripos($nama, 'Ariqa') !== false) {
        $carnelianRows[] = [
            'row' => $rIdx,
            'nama' => $nama,
            'nis' => $cells['C'] ?? '',
            'nisn' => $cells['E'] ?? '',
            'rombel_AQ' => $rombel
        ];
    }
}

echo "Total siswa Carnelian ditemukan di sheet 'Daftar Peserta Didik': " . count($carnelianRows) . "\n";
echo "Daftar rombel_AQ yang unik:\n";
$uniqueAQ = array_unique(array_column($carnelianRows, 'rombel_AQ'));
var_export($uniqueAQ);

echo "\n\nDetail Siswa Carnelian:\n";
foreach ($carnelianRows as $cr) {
    echo "Row: {$cr['row']} | Rombel: '{$cr['rombel_AQ']}' | NISN: '{$cr['nisn']}' | Nama: {$cr['nama']}\n";
}
