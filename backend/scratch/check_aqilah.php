<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $student_name = 'Aqilah Khanza Ramadhani';
    echo "=== DATABASE QUERY ===\n";
    $stmt = $pdo->prepare("SELECT id, nis, nama_lengkap, status_siswa FROM siswa WHERE nama_lengkap LIKE ?");
    $stmt->execute(['%' . $student_name . '%']);
    $siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($siswa);

    if (!empty($siswa)) {
        foreach ($siswa as $s) {
            echo "Anggota Rombel for Siswa ID {$s['id']} (NIS: {$s['nis']}):\n";
            $stmt2 = $pdo->prepare("
                SELECT ar.*, r.nama_rombel, ta.tahun, ta.semester as ta_semester 
                FROM anggota_rombel ar
                JOIN rombel r ON r.id = ar.rombel_id
                JOIN tahun_ajaran ta ON ta.id = ar.tahun_ajaran_id
                WHERE ar.siswa_id = ?
            ");
            $stmt2->execute([$s['id']]);
            $anggota = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            print_r($anggota);
        }
    }

    echo "\n=== ACTIVE TAHUN AJARAN ===\n";
    $stmt3 = $pdo->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif'");
    $ta = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    print_r($ta);

} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

echo "\n=== EXCEL FILE READ ===\n";
$excelPath = 'D:/xampp/htdocs/erapoteasy/docs/Template_Kolektif_Tengah_Safir.xlsx';
if (!file_exists($excelPath)) {
    echo "Excel file not found at $excelPath\n";
} else {
    try {
        $spreadsheet = IOFactory::load($excelPath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        
        echo "Kelas (C4): " . $sheet->getCell('C4')->getValue() . "\n";
        echo "Mapel (C5): " . $sheet->getCell('C5')->getValue() . "\n";
        echo "Jenis (C6): " . $sheet->getCell('C6')->getValue() . "\n";
        
        echo "\nRows in Excel:\n";
        for ($row = 10; $row <= $highestRow; $row++) {
            $no = $sheet->getCell('A' . $row)->getValue();
            $nis = $sheet->getCell('B' . $row)->getValue();
            $name = $sheet->getCell('C' . $row)->getValue();
            echo "Row $row: No: $no, NIS: '$nis', Name: '$name'\n";
        }
    } catch (Exception $e) {
        echo "Excel Error: " . $e->getMessage() . "\n";
    }
}
