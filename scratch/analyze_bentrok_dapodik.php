<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

// Ambil semua dari Dapodik
$dapodikList = [];
for ($r = 2; $r <= $sheet->getHighestRow(); $r++) {
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    $nis = trim((string)$sheet->getCell('C' . $r)->getFormattedValue());
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $kelas = trim((string)$sheet->getCell('H' . $r)->getFormattedValue());
    $dapodikList[] = ['nama' => $nama, 'nis' => $nis, 'nisn' => $nisn, 'kelas' => $kelas];
}

echo "=== DETAIL 8 KASUS BENTROK NIS JIKA DAPODIK DITERAPKAN LANGSUNG ===\n";

$bentrokPairs = [
    '09.25.0045' => ['Fahrian Haikal Ginting (ID 12)', 'FAEYZA MOUZA QHALISH (ID 43)'],
    '09.25.0059' => ['ISBAH FAJAR HAFIZ (ID 15)', 'Jahira Humairoh Manurung (ID 122)'],
    '09.25.0092' => ['Naufal Zidan Gunawan (ID 26)', 'NAURA DZAKIYYA SAKHI (ID 77)'],
    '09.25.0041' => ['DIMAS ALVINO WYDYANTORO (ID 41)', 'EARLYTA ARSYFA (ID 119)'],
    '09.25.0055' => ['Ghaisan Agha Gautama (ID 45)', 'Inaya Athifa (ID 121)'],
    '09.25.0101' => ['RAHMAD ARJUNA SIREGAR (ID 58)', 'RHALYNSA RIZKI (ID 125)'],
    '09.25.0047' => ['FARADILLA RAMADANI (ID 120)', 'Faiz Pintara Al-Hafidz (ID 423)'],
    '09.25.0068' => ['KHEYLA RASHIFA (ID 123)', 'KHAYLA AZKA RINANTI (ID 425)']
];

foreach ($bentrokPairs as $nis => $pair) {
    echo "\nNIS '$nis':\n";
    foreach ($pair as $desc) {
        preg_match('/ID (\d+)/', $desc, $m);
        $id = $m[1];
        $s = $pdo->query("SELECT id, nis, nisn, nama_lengkap, status_siswa FROM siswa WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
        // Cek di Dapodik
        $inDap = false;
        foreach ($dapodikList as $dp) {
            if ($dp['nisn'] === $s['nisn'] || stripos($dp['nama'], $s['nama_lengkap']) !== false) {
                echo "  - [ID $id] {$s['nama_lengkap']} (DB NIS: {$s['nis']}) -> ADA DI DAPODIK: NIS={$dp['nis']}, Kelas={$dp['kelas']}\n";
                $inDap = true;
                break;
            }
        }
        if (!$inDap) {
            echo "  - [ID $id] {$s['nama_lengkap']} (DB NIS: {$s['nis']}) -> TIDAK ADA DI DAPODIK!\n";
        }
    }
}

