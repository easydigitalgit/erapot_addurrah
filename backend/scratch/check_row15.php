<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $excelPath = 'D:/xampp/htdocs/erapoteasy/docs/Template_Kolektif_Tengah_Safir.xlsx';
    $spreadsheet = IOFactory::load($excelPath);
    $sheet = $spreadsheet->getActiveSheet();
    
    $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
    
    $strKelas = trim(str_replace(':', '', (string)$sheet->getCell('C4')->getValue()));
    $strMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
    $strJenis = strtolower(trim(str_replace(':', '', (string)$sheet->getCell('C6')->getValue())));

    echo "Kelas read from Excel: '$strKelas'\n";
    echo "Mapel read from Excel: '$strMapel'\n";
    echo "Jenis read from Excel: '$strJenis'\n";

    // Rombel query
    $stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE ?");
    $stmt->execute(['%' . $strKelas . '%']);
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Rombel in DB:\n";
    print_r($rombel);

    // Mapel query
    $stmt2 = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE nama_mapel LIKE ?");
    $stmt2->execute(['%' . $strMapel . '%']);
    $mapel = $stmt2->fetch(PDO::FETCH_ASSOC);
    echo "Mapel in DB:\n";
    print_r($mapel);

    $tahun_ajaran_id = 9;
    $semester = 'Genap';

    echo "\nRow 15 Details:\n";
    $nisCell = $sheet->getCell('B15');
    $nisVal = $nisCell->getValue();
    $nisDataType = $nisCell->getDataType();
    echo "NIS value: '$nisVal' (type: $nisDataType)\n";

    $nameCell = $sheet->getCell('C15');
    $nameVal = $nameCell->getValue();
    echo "Name value: '$nameVal'\n";

    // Query like NilaiKolektifController
    $nis_trim = trim((string)$nisVal);
    echo "trimmed NIS: '$nis_trim'\n";
    $stmt3 = $pdo->prepare("
        SELECT s.id, s.nis, s.nama_lengkap 
        FROM anggota_rombel ar
        JOIN siswa s ON s.id = ar.siswa_id
        WHERE s.nis = ?
          AND ar.rombel_id = ?
          AND ar.tahun_ajaran_id = ?
          AND ar.semester = ?
    ");
    $stmt3->execute([$nis_trim, $rombel['id'], $tahun_ajaran_id, $semester]);
    $siswa = $stmt3->fetch(PDO::FETCH_ASSOC);
    echo "Siswa match in query:\n";
    print_r($siswa);

    echo "\nAll columns for Row 15:\n";
    for ($c = 1; $c <= $highestColumnIndex; $c++) {
        $colLtr = Coordinate::stringFromColumnIndex($c);
        $header7 = $sheet->getCell($colLtr . '7')->getValue();
        $header9 = $sheet->getCell($colLtr . '9')->getValue();
        $val = $sheet->getCell($colLtr . '15')->getValue();
        $calcVal = $sheet->getCell($colLtr . '15')->getCalculatedValue();
        if ($header7 || $header9 || $val) {
            echo "Col $colLtr ($header9 / code $header7): raw='$val', calc='$calcVal'\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
