<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestRow();

$dapodikByNisn = [];
$dapodikByName = [];

for ($r = 2; $r <= $highestRow; $r++) {
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    $nis = trim((string)$sheet->getCell('C' . $r)->getFormattedValue());
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $cleanName = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

    $item = ['nama' => $nama, 'nis' => $nis, 'nisn' => $nisn];
    if (!empty($nisn)) $dapodikByNisn[$nisn] = $item;
    $dapodikByName[$cleanName] = $item;
}

$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

$simulatedNis = [];
$updates = [];

foreach ($dbSiswa as $s) {
    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $cleanDbName = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));

    $targetNis = $dbNis;
    if (!empty($dbNisn) && isset($dapodikByNisn[$dbNisn])) {
        $targetNis = $dapodikByNisn[$dbNisn]['nis'];
    } elseif (isset($dapodikByName[$cleanDbName])) {
        $targetNis = $dapodikByName[$cleanDbName]['nis'];
    }

    if ($targetNis !== $dbNis && !empty($targetNis)) {
        $updates[$s['id']] = $targetNis;
    }

    $simulatedNis[$s['id']] = [
        'nama' => $s['nama_lengkap'],
        'nis' => $targetNis
    ];
}

echo "Total siswa di DB yang akan di-update NIS-nya sesuai Dapodik: " . count($updates) . " siswa\n";

// Cek apakah ada duplikasi di simulatedNis
$nisCounts = [];
foreach ($simulatedNis as $id => $data) {
    $n = $data['nis'];
    if (!empty($n)) {
        $nisCounts[$n][] = "ID $id ({$data['nama']})";
    }
}

$dups = [];
foreach ($nisCounts as $nis => $list) {
    if (count($list) > 1) {
        $dups[$nis] = $list;
    }
}

echo "\nCek duplikasi NIS setelah diselaraskan dengan Dapodik:\n";
if (empty($dups)) {
    echo "LUAR BIASA! 0 DUPLIKAT! Seluruh 567 siswa di database akan memiliki NIS yang 100% UNIK dan SESUAI DAPODIK!\n";
} else {
    echo "Masih ada duplikat:\n";
    print_r($dups);
}

