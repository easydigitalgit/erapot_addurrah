<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestRow();

$dapodikByName = [];
$dapodikByNisn = [];

for ($r = 2; $r <= $highestRow; $r++) {
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    $nis = trim((string)$sheet->getCell('C' . $r)->getFormattedValue());
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

    $item = ['row' => $r, 'nama' => $nama, 'nis' => $nis, 'nisn' => $nisn, 'kelas' => $kelas];
    if ($nisn) $dapodikByNisn[$nisn] = $item;
    $dapodikByName[$clean] = $item;
}

$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

$diffs = [];
foreach ($dbSiswa as $s) {
    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));

    $dap = $dapodikByNisn[$dbNisn] ?? ($dapodikByName[$clean] ?? null);
    if ($dap && $dap['nis'] !== $dbNis) {
        $diffs[] = [
            'id' => $s['id'],
            'nama' => $s['nama_lengkap'],
            'nis_db' => $dbNis,
            'nis_dapodik' => $dap['nis'],
            'kelas' => $dap['kelas']
        ];
    }
}

echo "Total siswa beda NIS: " . count($diffs) . "\n\n";

// Cek perbedaan per kelas di Dapodik
$byKelas = [];
foreach ($diffs as $d) {
    $byKelas[$d['kelas']][] = $d;
}

foreach ($byKelas as $k => $list) {
    echo "=== KELAS $k (" . count($list) . " siswa beda NIS) ===\n";
    // Tampilkan 3 sampel per kelas
    for ($i = 0; $i < min(4, count($list)); $i++) {
        $item = $list[$i];
        echo "  - [ID {$item['id']}] {$item['nama']} | DB: '{$item['nis_db']}' -> Dapodik: '{$item['nis_dapodik']}'\n";
    }
}

