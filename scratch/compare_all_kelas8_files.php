<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=========================================================\n";
echo "1. KOMPARASI MASTER LM KELAS 8 (EXCEL VS DATABASE LOKAL)\n";
echo "=========================================================\n";

$lmExcelPath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/LM STS Kelas 8 T.A. 2026-2027.xlsx';
$reader = IOFactory::createReaderForFile($lmExcelPath);
$spreadsheet = $reader->load($lmExcelPath);
$sheet = $spreadsheet->getActiveSheet();
$lmExcelRows = $sheet->toArray();

// Ambil semua LM kelas 8 Ganjil Tengah di database
$dbLMs = $pdo->query("
    SELECT l.id, l.mapel_id, m.nama_mapel, l.kode_lm, l.deskripsi_lm 
    FROM raporsmpit.master_lm l
    JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    WHERE l.tingkat = 8 AND l.semester = 'Ganjil' AND l.kategori = 'Tengah'
    ORDER BY m.nama_mapel, l.kode_lm
")->fetchAll();

echo "LM di Database (Tingkat 8 Ganjil Tengah): " . count($dbLMs) . " LM\n";
foreach ($dbLMs as $l) {
    echo "  Mapel: {$l['nama_mapel']} | {$l['kode_lm']} | '{$l['deskripsi_lm']}'\n";
}

echo "\nLM di Excel Guru ($lmExcelPath):\n";
for ($i = 1; $i < count($lmExcelRows); $i++) {
    $row = $lmExcelRows[$i];
    $guru = $row[1] ?? '';
    $mapel = $row[2] ?? '';
    $kelas = $row[3] ?? '';
    $lm = $row[4] ?? '';
    if (!empty($mapel)) {
        echo "  [No $i] $guru | Mapel: $mapel | Kelas: $kelas\n";
        echo "       LM: " . str_replace("\n", " // ", trim($lm)) . "\n";
    }
}

