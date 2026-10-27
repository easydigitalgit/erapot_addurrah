<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== 1. CEK DUPLIKASI NIS DI DATABASE SISWA ===\n";
$dupNis = $pdo->query("
    SELECT nis, COUNT(*) as c, GROUP_CONCAT(id SEPARATOR ', ') as ids, GROUP_CONCAT(nama_lengkap SEPARATOR ' | ') as namas
    FROM siswa
    WHERE nis IS NOT NULL AND nis != ''
    GROUP BY nis
    HAVING c > 1
")->fetchAll(PDO::FETCH_ASSOC);

if (empty($dupNis)) {
    echo "Tidak ada NIS duplikat di tabel siswa (0 duplikat).\n";
} else {
    echo "DITEMUKAN " . count($dupNis) . " NIS DUPLIKAT DI TABEL SISWA:\n";
    foreach ($dupNis as $d) {
        echo "- NIS: '{$d['nis']}' (Total: {$d['c']}x) -> IDs: {$d['ids']} -> Nama: {$d['namas']}\n";
    }
}

// Cek NIS kosong di tabel siswa
$emptyNis = $pdo->query("SELECT id, nama_lengkap, status_siswa FROM siswa WHERE nis IS NULL OR nis = ''")->fetchAll(PDO::FETCH_ASSOC);
echo "Siswa dengan NIS kosong di DB: " . count($emptyNis) . "\n";

echo "\n=== 2. MEMERIKSA FILE DAPODIK EXCEL ===\n";
$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';

if (!file_exists($filePath)) {
    echo "File tidak ditemukan: $filePath\n";
    exit;
}

$spreadsheet = IOFactory::load($filePath);
$sheetNames = $spreadsheet->getSheetNames();
echo "Sheet names di Dapodik: " . implode(', ', $sheetNames) . "\n";

foreach ($sheetNames as $sname) {
    $sheet = $spreadsheet->getSheetByName($sname);
    echo "\n--- Sheet: $sname (Row: {$sheet->getHighestRow()}, Col: {$sheet->getHighestColumn()}) ---\n";
    // Cari baris header
    for ($r = 1; $r <= min(10, $sheet->getHighestRow()); $r++) {
        $rowVals = [];
        for ($c = 'A'; $c <= min('M', $sheet->getHighestColumn()); $c++) {
            $val = trim((string)$sheet->getCell($c . $r)->getFormattedValue());
            if ($val !== '') $rowVals[] = "$c: $val";
        }
        if (!empty($rowVals)) {
            echo "Row $r: " . implode(' | ', $rowVals) . "\n";
        }
    }
}

