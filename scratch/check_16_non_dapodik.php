<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = 'docs/dbrepair/Data Dapodik 2026-2027 (Update 27 Oktober 2026).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$dapodikNisns = [];
$dapodikNames = [];
for ($r = 2; $r <= $sheet->getHighestRow(); $r++) {
    $nisn = trim((string)$sheet->getCell('D' . $r)->getFormattedValue());
    $nama = trim((string)$sheet->getCell('B' . $r)->getFormattedValue());
    if ($nisn) $dapodikNisns[$nisn] = true;
    if ($nama) $dapodikNames[strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $nama))] = true;
}

$dbSiswa = $pdo->query("SELECT id, nis, nisn, nama_lengkap, status_siswa FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

echo "16 Siswa di DB yang TIDAK ada di file Dapodik 27 Okt 2026:\n";
foreach ($dbSiswa as $s) {
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));
    if (!isset($dapodikNisns[$s['nisn']]) && !isset($dapodikNames[$clean])) {
        // Cek rombel saat ini
        $rombel = $pdo->query("SELECT r.nama_rombel, r.tingkat FROM anggota_rombel ar JOIN rombel r ON r.id = ar.rombel_id WHERE ar.siswa_id = {$s['id']} AND ar.tahun_ajaran_id = 10")->fetch(PDO::FETCH_ASSOC);
        $rInfo = $rombel ? "{$rombel['tingkat']} - {$rombel['nama_rombel']}" : "Bukan Anggota Rombel TA 10";
        echo "- [ID {$s['id']}] {$s['nama_lengkap']} | NIS: '{$s['nis']}' | Status: {$s['status_siswa']} | Rombel TA 10: $rInfo\n";
    }
}

