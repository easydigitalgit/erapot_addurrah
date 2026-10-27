<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$highestRow = $sheet->getHighestRow();
echo "Total baris di Dapodik Excel: $highestRow (Header di row 1, Data: " . ($highestRow - 1) . " siswa)\n";

$dapodikData = [];
$dapodikNisMap = [];
$dapodikNisDup = [];

for ($r = 2; $r <= $highestRow; $r++) {
    $no = $sheet->getCell('A' . $r)->getFormattedValue();
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    $nis = trim((string)$sheet->getCell('C' . $r)->getFormattedValue());
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $jk = trim((string)$sheet->getCell('E' . $r)->getFormattedValue());
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());
    $status = trim((string)$sheet->getCell('J' . $r)->getFormattedValue());

    if (empty($nama) && empty($nis)) continue;

    $dapodikData[] = [
        'row' => $r,
        'no' => $no,
        'nama' => $nama,
        'nis' => $nis,
        'nisn' => $nisn,
        'jk' => $jk,
        'kelas' => $kelas,
        'status' => $status
    ];

    if (!empty($nis)) {
        if (isset($dapodikNisMap[$nis])) {
            $dapodikNisDup[$nis][] = $nama;
            $dapodikNisDup[$nis][] = $dapodikNisMap[$nis]['nama'];
        } else {
            $dapodikNisMap[$nis] = ['row' => $r, 'nama' => $nama, 'nisn' => $nisn, 'kelas' => $kelas];
        }
    }
}

echo "\n=== 1. CEK DUPLIKASI NIS DI DALAM FILE DAPODIK EXCEL ===\n";
if (empty($dapodikNisDup)) {
    echo "Tidak ada duplikasi NIS di dalam file Dapodik (Semua NIS unik 100%).\n";
} else {
    echo "DITEMUKAN DUPLIKASI NIS DI DAPODIK EXCEL:\n";
    foreach ($dapodikNisDup as $nis => $namas) {
        echo "- NIS '$nis': " . implode(', ', array_unique($namas)) . "\n";
    }
}

echo "\n=== 2. CEK 3 SISWA DUPLIKAT DI DB TERHADAP DAPODIK ===\n";
$targetNames = [
    'Muhammad Akbar Wijaya', 'MUHAMMAD AFKHAR AYUB',
    'Muhammad Raihan Rasyiddin', 'MYSHA ZAHIRA SACHI',
    'FABIAN ARYA BIMANTARA', 'FADHIL RAJABI'
];

foreach ($targetNames as $tName) {
    echo "Pencarian '$tName':\n";
    // Cari di DB
    $dbRows = $pdo->query("SELECT id, nis, nisn, nama_lengkap, status_siswa FROM siswa WHERE nama_lengkap LIKE '%$tName%'")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dbRows as $dbr) {
        echo "  [DB] ID {$dbr['id']} | NIS: {$dbr['nis']} | NISN: {$dbr['nisn']} | Status: {$dbr['status_siswa']}\n";
    }
    // Cari di Dapodik
    $foundDapodik = false;
    foreach ($dapodikData as $dp) {
        if (stripos($dp['nama'], $tName) !== false || (SOUNDEX($dp['nama']) == SOUNDEX($tName))) {
            echo "  [DAPODIK] Row {$dp['row']} | NIS: {$dp['nis']} | NISN: {$dp['nisn']} | Kelas: {$dp['kelas']} | Status: {$dp['status']}\n";
            $foundDapodik = true;
        }
    }
    if (!$foundDapodik) echo "  [DAPODIK] TIDAK DITEMUKAN!\n";
}

