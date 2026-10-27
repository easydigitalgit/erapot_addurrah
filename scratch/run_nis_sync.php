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

$collisionIds = [12, 77, 119, 120, 121, 122, 123, 125];

$sqlStatements = [];
$sqlStatements[] = "-- ==============================================================================";
$sqlStatements[] = "-- SKRIP REPAIR 07: SINKRONISASI NIS RESMI DAPODIK 27 OKTOBER 2026";
$sqlStatements[] = "-- ==============================================================================";
$sqlStatements[] = "-- Menyelaraskan NIS siswa aktif dengan data resmi Dapodik (Update 27 Okt 2026).";
$sqlStatements[] = "-- Menghilangkan seluruh duplikasi NIS di database (0 duplikat).";
$sqlStatements[] = "-- ==============================================================================\n";
$sqlStatements[] = "USE `raporsmpit`;\n";

$updateList = [];

// Step 1: Berikan suffix -A pada 8 siswa non-dapodik terlebih dahulu agar tidak bentrok
$sqlStatements[] = "-- 1. Amankan 8 siswa non-dapodik yang nomor NIS-nya berbenturan dengan urutan baru Dapodik";
foreach ($collisionIds as $cid) {
    $s = $pdo->query("SELECT id, nis, nama_lengkap FROM siswa WHERE id = $cid")->fetch(PDO::FETCH_ASSOC);
    $newNis = trim($s['nis']) . '-A';
    $namaEsc = addslashes($s['nama_lengkap']);
    $sqlStatements[] = "UPDATE siswa SET nis = '$newNis' WHERE id = $cid; -- {$s['nama_lengkap']} (Lama: {$s['nis']})";
    $updateList[] = ['id' => $cid, 'nis' => $newNis];
}

$sqlStatements[] = "\n-- 2. Update NIS seluruh siswa aktif sesuai berkas resmi Dapodik 27 Oktober 2026";
foreach ($dbSiswa as $s) {
    $sid = $s['id'];
    if (in_array($sid, $collisionIds)) continue; // sudah di step 1

    $dbNis = trim($s['nis'] ?? '');
    $dbNisn = trim($s['nisn'] ?? '');
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));

    $dap = $dapodikByNisn[$dbNisn] ?? ($dapodikByName[$clean] ?? null);
    if ($dap) {
        $finalNis = $dap['nis'];
        if ($finalNis !== $dbNis && !empty($finalNis)) {
            $namaEsc = addslashes($s['nama_lengkap']);
            $sqlStatements[] = "UPDATE siswa SET nis = '$finalNis' WHERE id = $sid; -- $namaEsc (Lama: $dbNis)";
            $updateList[] = ['id' => $sid, 'nis' => $finalNis];
        }
    }
}

$sqlContent = implode("\n", $sqlStatements) . "\n";
file_put_contents('docs/dbrepair/07_sinkronisasi_nis_dapodik.sql', $sqlContent);
echo "File docs/dbrepair/07_sinkronisasi_nis_dapodik.sql berhasil dibuat (" . count($updateList) . " update statements).\n";

// Sekarang eksekusi langsung ke database lokal!
$pdo->beginTransaction();
$execCount = 0;
foreach ($updateList as $u) {
    $stmt = $pdo->prepare("UPDATE siswa SET nis = ? WHERE id = ?");
    $stmt->execute([$u['nis'], $u['id']]);
    $execCount++;
}
$pdo->commit();
echo "SUKSES EKSEKUSI! Sebanyak $execCount baris NIS siswa berhasil diperbarui di database raporsmpit.\n";

// Verifikasi akhir
$checkDups = $pdo->query("SELECT nis, COUNT(*) as c, GROUP_CONCAT(id) as ids, GROUP_CONCAT(nama_lengkap SEPARATOR ' | ') as namas FROM siswa GROUP BY nis HAVING c > 1")->fetchAll(PDO::FETCH_ASSOC);
echo "Jumlah NIS Duplikat di database saat ini: " . count($checkDups) . "\n";
if (!empty($checkDups)) {
    print_r($checkDups);
} else {
    echo "VERIFIKASI BERHASIL: 0 NIS DUPLIKAT DI SELURUH DATABASE SISWA!\n";
}

