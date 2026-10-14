<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../docs/Data Siswa Dapodik Perkelas.xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(true);
$reader->setLoadSheetsOnly(['VII']);
$spreadsheet = $reader->load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$targetNames = [
    'Ariqa', 'Ashika', 'Ataya', 'Jihan Talita', 'Keisha Amila', 'Nadifa', 'Qabsha', 'Aenchi', 'Anggitta', 'Ashillah'
];

$carnelianRows = [];

foreach ($sheet->getRowIterator() as $row) {
    $rIdx = $row->getRowIndex();
    if ($rIdx > 200) break; // kelas VII biasanya tidak lebih dari 200 baris
    
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    $cells = [];
    foreach ($cellIterator as $cell) {
        $cells[$cell->getColumn()] = $cell->getValue();
    }
    
    $nama = trim((string)($cells['B'] ?? ''));
    $rombel = trim((string)($cells['AQ'] ?? ''));
    
    if (empty($nama) && empty($rombel)) continue;
    
    if (stripos($rombel, 'Carnelian') !== false) {
        $carnelianRows[] = [
            'row' => $rIdx,
            'nama' => $nama,
            'nis' => $cells['C'] ?? '',
            'nisn' => $cells['E'] ?? '',
            'rombel_AQ' => $rombel
        ];
    }
}

echo "Total siswa Carnelian ditemukan di sheet VII: " . count($carnelianRows) . "\n";
echo "Daftar rombel_AQ yang unik:\n";
$uniqueAQ = array_unique(array_column($carnelianRows, 'rombel_AQ'));
print_r($uniqueAQ);

echo "\nSemua siswa Carnelian:\n";
foreach ($carnelianRows as $cr) {
    echo "Row: {$cr['row']} | Rombel: '{$cr['rombel_AQ']}' | NIS: '{$cr['nis']}' | NISN: '{$cr['nisn']}' | Nama: {$cr['nama']}\n";
}
