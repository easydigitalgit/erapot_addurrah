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
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

    $item = ['nama' => $nama, 'nis' => $nis, 'nisn' => $nisn];
    if ($nisn) $dapodikByNisn[$nisn] = $item;
    $dapodikByName[$clean] = $item;
}

$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

$updated = 0;
$collisionIds = [12, 77, 119, 120, 121, 122, 123, 125];

foreach ($dbSiswa as $s) {
    $sid = $s['id'];
    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));

    $dap = $dapodikByNisn[$dbNisn] ?? ($dapodikByName[$clean] ?? null);
    if ($dap) {
        $finalNis = $dap['nis'];
    } else {
        if (in_array($sid, $collisionIds)) {
            $finalNis = $dbNis . '-A';
        } else {
            $finalNis = $dbNis;
        }
    }

    if ($finalNis !== $dbNis) {
        $updated++;
    }
}

echo "Total siswa yang akan di-update NIS-nya: $updated siswa dari 567 siswa.\n";

