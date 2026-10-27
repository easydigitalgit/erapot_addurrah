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
    $status = trim((string)$sheet->getCell('J' . $r)->getFormattedValue());

    $cleanName = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama));

    $item = [
        'row' => $r,
        'nama' => $nama,
        'nis' => $nis,
        'nisn' => $nisn,
        'kelas' => $kelas,
        'status' => $status,
        'cleanName' => $cleanName
    ];

    if (!empty($nisn)) {
        $dapodikByNisn[$nisn] = $item;
    }
    $dapodikByName[$cleanName] = $item;
}

echo "Total siswa di Dapodik: " . count($dapodikByName) . " siswa unik berdasarkan nama.\n";

// Ambil semua siswa dari database
$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap, status_siswa FROM siswa ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo "Total siswa di Database: " . count($dbSiswa) . " siswa.\n";

$matched = 0;
$nisBeda = [];
$nisnBeda = [];
$notInDapodik = [];

foreach ($dbSiswa as $s) {
    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $dbName = trim($s['nama_lengkap'] ?? '');
    $cleanDbName = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $dbName));

    $dapodikItem = null;
    if (!empty($dbNisn) && isset($dapodikByNisn[$dbNisn])) {
        $dapodikItem = $dapodikByNisn[$dbNisn];
    } elseif (isset($dapodikByName[$cleanDbName])) {
        $dapodikItem = $dapodikByName[$cleanDbName];
    } else {
        // Coba soundex atau fuzzy
        foreach ($dapodikByName as $cName => $item) {
            if (soundex($cName) === soundex($cleanDbName) && (levenshtein($cName, $cleanDbName) < 5 || strpos($cleanDbName, $cName) !== false || strpos($cName, $cleanDbName) !== false)) {
                $dapodikItem = $item;
                break;
            }
        }
    }

    if ($dapodikItem) {
        $matched++;
        $dapNis = $dapodikItem['nis'];
        $dapNisn = $dapodikItem['nisn'];

        if ($dbNis !== $dapNis) {
            $nisBeda[] = [
                'id' => $s['id'],
                'nama' => $dbName,
                'dapodik_nama' => $dapodikItem['nama'],
                'nis_db' => $dbNis,
                'nis_dapodik' => $dapNis,
                'nisn' => $dbNisn,
                'kelas' => $dapodikItem['kelas'],
                'status' => $s['status_siswa']
            ];
        }

        if ($dbNisn !== $dapNisn && !empty($dapNisn)) {
            $nisnBeda[] = [
                'id' => $s['id'],
                'nama' => $dbName,
                'nisn_db' => $dbNisn,
                'nisn_dapodik' => $dapNisn
            ];
        }
    } else {
        $notInDapodik[] = [
            'id' => $s['id'],
            'nama' => $dbName,
            'nis' => $dbNis,
            'nisn' => $dbNisn,
            'status' => $s['status_siswa']
        ];
    }
}

echo "\n============================================================\n";
echo "HASIL KOMPARASI DATABASE SISWA vs DAPODIK EXCEL:\n";
echo "============================================================\n";
echo "Siswa DB Berhasil Dicocokkan : $matched siswa\n";
echo "Siswa DB Beda NIS dg Dapodik : " . count($nisBeda) . " siswa\n";
echo "Siswa DB Beda NISN dg Dapodik: " . count($nisnBeda) . " siswa\n";
echo "Siswa DB Tidak Ada di Dapodik: " . count($notInDapodik) . " siswa (Cth: alumni / non-aktif)\n";

if (!empty($nisBeda)) {
    echo "\nDAFTAR SISWA YANG NIS DI DATABASE BERBEDA DENGAN DAPODIK:\n";
    foreach ($nisBeda as $nb) {
        echo sprintf("- [ID %3d] %-30s | DB: %-11s -> DAPODIK: %-11s | Kelas: %s\n", 
            $nb['id'], $nb['nama'], $nb['nis_db'], $nb['nis_dapodik'], $nb['kelas']);
    }
}

