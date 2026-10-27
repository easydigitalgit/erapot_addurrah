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

echo "Pemeriksaan 16 Siswa non-Dapodik:\n";
foreach ($dbSiswa as $s) {
    $clean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $s['nama_lengkap']));
    if (!isset($dapodikNisns[$s['nisn']]) && !isset($dapodikNames[$clean])) {
        $sid = $s['id'];
        $fCount = $pdo->query("SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id = $sid AND tahun_ajaran_id = 10")->fetchColumn();
        $sCount = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif WHERE siswa_id = $sid AND tahun_ajaran_id = 10")->fetchColumn();
        $rCount = $pdo->query("SELECT COUNT(*) FROM nilai_rapor WHERE siswa_id = $sid AND tahun_ajaran_id = 10")->fetchColumn();
        echo "- [ID $sid] {$s['nama_lengkap']} | Formatif TA10: $fCount | Sumatif TA10: $sCount | Rapor TA10: $rCount\n";
    }
}

