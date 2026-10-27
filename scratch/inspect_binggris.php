<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Cek guru B Inggris di guru_mapel
$guruBing = $pdo->query("
    SELECT gm.id, gm.guru_id, gt.nama_lengkap, gm.mapel_id, m.nama_mapel, gm.rombel_id, r.nama_rombel 
    FROM raporsmpit.guru_mapel gm
    JOIN raporsmpit.guru_tendik gt ON gm.guru_id = gt.id
    JOIN raporsmpit.mata_pelajaran m ON gm.mapel_id = m.id
    JOIN raporsmpit.rombel r ON gm.rombel_id = r.id
    WHERE m.id = 6 AND r.tingkat = 'VIII' AND gm.tahun_ajaran_id = 10;
")->fetchAll();

echo "Guru Mapel Bahasa Inggris Kelas 8 di Database:\n";
foreach ($guruBing as $g) {
    echo "  Rombel {$g['nama_rombel']} (ID {$g['rombel_id']}) -> Guru: {$g['nama_lengkap']} (Guru ID {$g['guru_id']})\n";
}

$filePath = 'docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 (Intan, Kyanite, Safir, Granit & Topaz) Oktober 2026/LM dan DKN STS B. Inggris Kls 8  Intan, Kyanite, Safir, Granit, Topaz(Oktober 2026).xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(false);
$spreadsheet = $reader->load($filePath);

echo "\nSheets di $filePath:\n";
foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestDataRow();
    echo "\nSheet: $sheetName (Max Row: $highestRow)\n";
    for ($r = 8; $r <= min(12, $highestRow); $r++) {
        $rowVals = [];
        for ($c = 'A'; $c <= 'N'; $c++) {
            $v = $sheet->getCell($c . $r)->getValue();
            if ($v !== null && $v !== '') $rowVals[] = "$c:$v";
        }
        if ($rowVals) echo "  Row $r: " . implode(" | ", array_slice($rowVals, 0, 8)) . "\n";
    }
}

