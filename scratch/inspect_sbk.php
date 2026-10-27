<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$guruSbk = $pdo->query("
    SELECT gm.id, gm.guru_id, gt.nama_lengkap, gm.mapel_id, m.nama_mapel, gm.rombel_id, r.nama_rombel 
    FROM raporsmpit.guru_mapel gm
    JOIN raporsmpit.guru_tendik gt ON gm.guru_id = gt.id
    JOIN raporsmpit.mata_pelajaran m ON gm.mapel_id = m.id
    JOIN raporsmpit.rombel r ON gm.rombel_id = r.id
    WHERE m.id = 8 AND r.id IN (73, 76) AND gm.tahun_ajaran_id = 10;
")->fetchAll();

echo "Guru Seni Budaya di Topaz & Safir:\n";
foreach ($guruSbk as $g) {
    echo "  Rombel {$g['nama_rombel']} (ID {$g['rombel_id']}) -> Guru: {$g['nama_lengkap']} (Guru ID {$g['guru_id']})\n";
}

$files = [
    'Safir' => 'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Safir.xlsx',
    'Topaz' => 'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Topaz fix.xlsx'
];

foreach ($files as $name => $f) {
    echo "\n=== FILE: $name ($f) ===\n";
    $reader = IOFactory::createReaderForFile($f);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($f);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestDataRow();
    echo "Max Row: $highestRow\n";
    echo "Row 4 (Kelas): " . $sheet->getCell('A4')->getValue() . " | " . $sheet->getCell('C4')->getValue() . "\n";
    echo "Row 5 (Mapel): " . $sheet->getCell('A5')->getValue() . " | " . $sheet->getCell('C5')->getValue() . "\n";
    echo "Row 7 (Meta):  A7=" . $sheet->getCell('A7')->getValue() . " | B7=" . $sheet->getCell('B7')->getValue() . " | C7=" . $sheet->getCell('C7')->getValue() . " | D7=" . $sheet->getCell('D7')->getValue() . " | E7=" . $sheet->getCell('E7')->getValue() . " | F7=" . $sheet->getCell('F7')->getValue() . " | G7=" . $sheet->getCell('G7')->getValue() . " | H7=" . $sheet->getCell('H7')->getValue() . "\n";
    echo "Row 8 (Header): B8=" . $sheet->getCell('B8')->getValue() . " | C8=" . $sheet->getCell('C8')->getValue() . " | D8=" . $sheet->getCell('D8')->getValue() . " | F8=" . $sheet->getCell('F8')->getValue() . " | H8=" . $sheet->getCell('H8')->getValue() . "\n";
    echo "Row 9 (Sub):    D9=" . $sheet->getCell('D9')->getValue() . " | E9=" . $sheet->getCell('E9')->getValue() . " | F9=" . $sheet->getCell('F9')->getValue() . " | G9=" . $sheet->getCell('G9')->getValue() . " | H9=" . $sheet->getCell('H9')->getValue() . "\n";
    for ($r = 10; $r <= 13; $r++) {
        $no   = $sheet->getCell("A$r")->getValue();
        $nis  = $sheet->getCell("B$r")->getValue();
        $nama = $sheet->getCell("C$r")->getValue();
        $d    = $sheet->getCell("D$r")->getValue();
        $e    = $sheet->getCell("E$r")->getValue();
        $f    = $sheet->getCell("F$r")->getValue();
        $g    = $sheet->getCell("G$r")->getValue();
        $h    = $sheet->getCell("H$r")->getValue();
        echo "  Row $r: [$no] NIS:$nis | $nama | D:$d, E:$e, F:$f, G:$g, H:$h\n";
    }
}

