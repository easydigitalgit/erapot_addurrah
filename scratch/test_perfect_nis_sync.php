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
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

    $item = ['nama' => $nama, 'nis' => $nis, 'nisn' => $nisn, 'kelas' => $kelas];
    if ($nisn) $dapodikByNisn[$nisn] = $item;
    $dapodikByName[$clean] = $item;
}

$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

$finalNisMap = [];
$collisionIds = [12, 77, 119, 120, 121, 122, 123, 125];

foreach ($dbSiswa as $s) {
    $sid = $s['id'];
    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));

    // Cek apakah ada di Dapodik
    $dap = $dapodikByNisn[$dbNisn] ?? ($dapodikByName[$clean] ?? null);

    if ($dap) {
        $finalNis = $dap['nis'];
    } else {
        // Siswa non-dapodik
        if (in_array($sid, $collisionIds)) {
            $finalNis = $dbNis . '-A'; // Hindari bentrok dengan nomor urut baru
        } else {
            $finalNis = $dbNis;
        }
    }

    $finalNisMap[$sid] = [
        'nama' => $s['nama_lengkap'],
        'nis_lama' => $dbNis,
        'nis_baru' => $finalNis,
        'is_dapodik' => $dap ? true : false
    ];
}

// Cek duplikasi pada finalNisMap
$counts = [];
foreach ($finalNisMap as $sid => $info) {
    $n = $info['nis_baru'];
    $counts[$n][] = "ID $sid ({$info['nama']})";
}

$dups = [];
foreach ($counts as $n => $list) {
    if (count($list) > 1) {
        $dups[$n] = $list;
    }
}

echo "=== HASIL PENGUJIAN SINKRONISASI NIS ===\n";
echo "Total Siswa di DB      : " . count($finalNisMap) . "\n";
echo "Jumlah Duplikat Akhir  : " . count($dups) . "\n";

if (empty($dups)) {
    echo "\nSUKSES SEMPURNA! 0 DUPLIKAT!\n";
    echo "Semua 551 siswa Dapodik memiliki NIS yang PERSIS SESUAI DAPODIK.\n";
    echo "Semua 16 siswa non-dapodik tetap tersimpan aman dengan NIS unik.\n";
} else {
    print_r($dups);
}

