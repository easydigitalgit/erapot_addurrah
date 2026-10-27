<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('8 TOPAZ');

echo "=== MEMERIKSA SISWA DI EXCEL PAIBP 8 TOPAZ TERHADAP DATABASE ===\n";

for ($r = 10; $r <= 45; $r++) {
    $no = $sheet->getCell('A' . $r)->getFormattedValue();
    $namaExcel = trim($sheet->getCell('B' . $r)->getFormattedValue());
    if (empty($namaExcel)) continue;

    $nh1 = $sheet->getCell('C' . $r)->getFormattedValue();
    $nh2 = $sheet->getCell('D' . $r)->getFormattedValue();
    $uh1 = $sheet->getCell('H' . $r)->getFormattedValue();
    $uh2 = $sheet->getCell('I' . $r)->getFormattedValue();
    $sts = $sheet->getCell('M' . $r)->getFormattedValue();
    $nr  = $sheet->getCell('P' . $r)->getFormattedValue();

    // Cari siswa di DB
    $stmt = $pdo->prepare("
        SELECT s.id, s.nama_lengkap 
        FROM siswa s 
        JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
        JOIN rombel r ON r.id = ar.rombel_id 
        WHERE r.nama_rombel = 'Topaz' 
          AND (s.nama_lengkap LIKE ? OR SOUNDEX(s.nama_lengkap) = SOUNDEX(?))
    ");
    $stmt->execute(["%$namaExcel%", $namaExcel]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($s) {
        $siswa_id = $s['id'];
        // Cek formatif di DB
        $fCount = $pdo->query("SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id = $siswa_id AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();
        $sCount = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif WHERE siswa_id = $siswa_id AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();
        $rCount = $pdo->query("SELECT COUNT(*) FROM nilai_rapor WHERE siswa_id = $siswa_id AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();

        echo sprintf("%2d. [%-30s] -> DB [ID %2d]: Formatif=%d, Sumatif=%d, Rapor=%d (Excel NR: %s)\n", $no, $namaExcel, $siswa_id, $fCount, $sCount, $rCount, $nr);
    } else {
        echo sprintf("%2d. [%-30s] -> TIDAK DITEMUKAN DI KELAS TOPAZ!\n", $no, $namaExcel);
    }
}

