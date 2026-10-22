<?php
require 'backend/vendor/autoload.php';

$pdo = new PDO("mysql:host=127.0.0.1;dbname=raporsmpit;charset=utf8mb4", "root", "");

$file = 'docs/Template_Kolektif_Tengah_Amazonite.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestDataRow();

echo "COMPARING EXCEL VS DATABASE FOR AMAZONITE (TA: 10, Mapel: 6):\n";
echo str_repeat('-', 120) . "\n";
echo sprintf("%-4s | %-12s | %-25s | %-20s | %-20s | %-15s\n", "No", "NIS", "Nama", "Excel (D,E,F,G,H,Rap)", "DB Formatif (Tugas)", "DB Formatif (Ulg)", "DB PTS");
echo str_repeat('-', 120) . "\n";

for ($r = 10; $r <= $highestRow; $r++) {
    $no = $sheet->getCell('A' . $r)->getValue();
    $nis = trim((string)$sheet->getCell('B' . $r)->getValue());
    $nama = trim((string)$sheet->getCell('C' . $r)->getValue());
    if (empty($nis)) continue;

    $d = $sheet->getCell('D' . $r)->getCalculatedValue(); // NH 1
    $e = $sheet->getCell('E' . $r)->getCalculatedValue(); // NH 2
    $f = $sheet->getCell('F' . $r)->getCalculatedValue(); // UH 1
    $g = $sheet->getCell('G' . $r)->getCalculatedValue(); // UH 2
    $h = $sheet->getCell('H' . $r)->getCalculatedValue(); // PTS
    $i = round((float)$sheet->getCell('I' . $r)->getCalculatedValue(), 1); // Rapor

    // Query DB
    $stmt = $pdo->prepare("SELECT id FROM siswa WHERE nis = ?");
    $stmt->execute([$nis]);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    $siswa_id = $siswa ? $siswa['id'] : 0;

    $dbTugas = [];
    $dbUlang = [];
    if ($siswa_id) {
        $stmt = $pdo->prepare("SELECT pertemuan, nilai_angka FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = 6 AND rombel_id = 77 AND tahun_ajaran_id = 10 AND semester = 'Ganjil' AND kategori = 'Tengah Semester' ORDER BY jenis_penilaian, pertemuan");
        $stmt->execute([$siswa_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt2 = $pdo->prepare("SELECT pertemuan, nilai_angka, jenis_penilaian FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = 6 AND rombel_id = 77 AND tahun_ajaran_id = 10 AND semester = 'Ganjil' AND kategori = 'Tengah Semester'");
        $stmt2->execute([$siswa_id]);
        foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $nf) {
            if ($nf['jenis_penilaian'] == 'Tugas') {
                $dbTugas[] = "P{$nf['pertemuan']}=" . round($nf['nilai_angka']);
            } else {
                $dbUlang[] = "P{$nf['pertemuan']}=" . round($nf['nilai_angka']);
            }
        }

        $stmt3 = $pdo->prepare("SELECT nilai FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = 6 AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester' AND jenis_sumatif = 'pts'");
        $stmt3->execute([$siswa_id]);
        $ptsRow = $stmt3->fetch(PDO::FETCH_ASSOC);
        $dbPts = $ptsRow ? round($ptsRow['nilai']) : '-';
    } else {
        $dbPts = 'NO SISWA';
    }

    $excelStr = "NH:[$d,$e] UH:[$f,$g] P:$h";
    $tugasStr = implode(',', $dbTugas) ?: '-';
    $ulangStr = implode(',', $dbUlang) ?: '-';

    echo sprintf("%-4s | %-12s | %-25s | %-23s | %-10s | %-10s | %-6s\n", 
        $no, $nis, substr($nama, 0, 25), $excelStr, $tugasStr, $ulangStr, $dbPts);
}
