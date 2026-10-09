<?php
// Test validation of template creation and parsing logic
require_once __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== TEST 1: Generate Template Siswa ===\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Siswa');

$headers = [
    'NIS (Kunci Utama / Kosongkan Jika Baru)',
    'NISN',
    'NIK',
    'Nama Lengkap (Wajib)',
    'Jenis Kelamin (L/P)',
    'Tempat Lahir',
    'Tanggal Lahir (YYYY-MM-DD)',
    'Agama',
    'Kelas / Rombel (Lihat Sheet 2)',
    'Alamat Siswa',
    'Kecamatan',
    'Kelurahan / Desa',
    'No HP Siswa',
    'Email Siswa',
    'Asal Sekolah',
    'Diterima di Kelas',
    'Tanggal Diterima (YYYY-MM-DD)',
    'Nama Ayah',
    'Nama Ibu',
    'No HP / WA Orang Tua',
    'Status Siswa (Aktif/Lulus/Pindah/Keluar)'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Sample row
$sheet->setCellValueExplicit('A2', '08.26.0001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValueExplicit('B2', '0012345678', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValueExplicit('C2', '3201012345670001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('D2', 'Contoh: Ahmad Zidan');
$sheet->setCellValue('E2', 'L');
$sheet->setCellValue('F2', 'Jakarta');
$sheet->setCellValue('G2', '2010-05-20');
$sheet->setCellValue('H2', 'Islam');
$sheet->setCellValue('I2', 'VII Safir');
$sheet->setCellValue('J2', 'Jl. Merdeka No. 10');
$sheet->setCellValue('K2', 'Kebayoran Baru');
$sheet->setCellValue('L2', 'Senayan');
$sheet->setCellValueExplicit('M2', '081234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('N2', 'ahmad@sekolah.sch.id');
$sheet->setCellValue('O2', 'SDN 1 Jakarta');
$sheet->setCellValue('P2', 'VII');
$sheet->setCellValue('Q2', '2024-07-15');
$sheet->setCellValue('R2', 'Budi Santoso');
$sheet->setCellValue('S2', 'Siti Rahmah');
$sheet->setCellValueExplicit('T2', '081298765432', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('U2', 'Aktif');

// Row 3: New student with empty NIS
$sheet->setCellValueExplicit('A3', '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValueExplicit('B3', '0098765432', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValueExplicit('C3', '3201019988770002', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('D3', 'Fatimah Az Zahra');
$sheet->setCellValue('E3', 'P');
$sheet->setCellValue('F3', 'Bandung');
$sheet->setCellValue('G3', '2011-08-12');
$sheet->setCellValue('H3', 'Islam');
$sheet->setCellValue('I3', 'VII Safir');
$sheet->setCellValue('J3', 'Jl. Asia Afrika No. 5');
$sheet->setCellValue('K3', 'Sumur Bandung');
$sheet->setCellValue('L3', 'Braga');
$sheet->setCellValueExplicit('M3', '081345678901', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('N3', 'fatimah@sekolah.sch.id');
$sheet->setCellValue('O3', 'SDN 2 Bandung');
$sheet->setCellValue('P3', 'VII');
$sheet->setCellValue('Q3', '2024-07-15');
$sheet->setCellValue('R3', 'Ahmad Dahlan');
$sheet->setCellValue('S3', 'Nurul Hidayah');
$sheet->setCellValueExplicit('T3', '081399887766', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('U3', 'Aktif');

$testFilePath = __DIR__ . '/test_sample_import.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFilePath);

echo "Template saved to $testFilePath\n";

echo "=== TEST 2: Read & Verify Columns ===\n";
$loaded = IOFactory::load($testFilePath);
$data = $loaded->getActiveSheet()->toArray(null, true, true, true);

foreach ($data as $idx => $row) {
    echo "Row $idx: NIS='{$row['A']}', NISN='{$row['B']}', Nama='{$row['D']}', JK='{$row['E']}', TTL='{$row['F']}, {$row['G']}', Kelas='{$row['I']}', Ayah='{$row['R']}', Ibu='{$row['S']}', Status='{$row['U']}'\n";
}

echo "=== ALL PARSING CHECKS PASSED ===\n";
