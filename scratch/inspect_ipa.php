<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Cek guru IPA di guru_mapel
$guruIpa = $pdo->query("
    SELECT gm.id, gm.guru_id, gt.nama_lengkap, gm.mapel_id, m.nama_mapel, gm.rombel_id, r.nama_rombel 
    FROM raporsmpit.guru_mapel gm
    JOIN raporsmpit.guru_tendik gt ON gm.guru_id = gt.id
    JOIN raporsmpit.mata_pelajaran m ON gm.mapel_id = m.id
    JOIN raporsmpit.rombel r ON gm.rombel_id = r.id
    WHERE m.nama_mapel LIKE '%IPA%' AND r.tingkat = 'VIII' AND gm.tahun_ajaran_id = 10;
")->fetchAll();

echo "Guru Mapel IPA Kelas 8 di Database:\n";
foreach ($guruIpa as $g) {
    echo "  Rombel {$g['nama_rombel']} (ID {$g['rombel_id']}) -> Guru: {$g['nama_lengkap']} (Guru ID {$g['guru_id']})\n";
}

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN STS GANJIL IPA KELAS 8 2026-2027.xlsx';
$reader = IOFactory::createReaderForFile($filePath);
$reader->setReadDataOnly(false);
$spreadsheet = $reader->load($filePath);

echo "\nSheets di $filePath:\n";
foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestDataRow();
    echo "Sheet: $sheetName (Max Row: $highestRow)\n";
    echo "  Row 8: A=" . $sheet->getCell('A8')->getValue() . " | B=" . $sheet->getCell('B8')->getValue() . " | C=" . $sheet->getCell('C8')->getValue() . " | E=" . $sheet->getCell('E8')->getValue() . " | G=" . $sheet->getCell('G8')->getValue() . "\n";
    echo "  Row 9: C=" . $sheet->getCell('C9')->getValue() . " | D=" . $sheet->getCell('D9')->getValue() . " | E=" . $sheet->getCell('E9')->getValue() . " | F=" . $sheet->getCell('F9')->getValue() . " | G=" . $sheet->getCell('G9')->getValue() . "\n";
    for ($r = 10; $r <= 12; $r++) {
        $no   = $sheet->getCell("A$r")->getValue();
        $nama = trim((string)$sheet->getCell("B$r")->getValue());
        $nh1  = $sheet->getCell("C$r")->getCalculatedValue();
        $nh2  = $sheet->getCell("D$r")->getCalculatedValue();
        $uh1  = $sheet->getCell("E$r")->getCalculatedValue();
        $uh2  = $sheet->getCell("F$r")->getCalculatedValue();
        $sts  = $sheet->getCell("G$r")->getCalculatedValue();
        echo "    Sample Row $r: [$no] $nama | NH1: $nh1, NH2: $nh2 | UH1: $uh1, UH2: $uh2 | STS: $sts\n";
    }
}

