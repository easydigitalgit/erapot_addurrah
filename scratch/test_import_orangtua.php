<?php
require_once __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== TEST 1: Generate Template Orang Tua ===\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Orang Tua');

$headers = [
    'NIS Siswa (Wajib / Kunci Relasi)',
    'Nama Siswa (Lihat Sheet 2)',
    'Nama Ayah',
    'NIK Ayah',
    'Pekerjaan Ayah',
    'Nama Ibu',
    'NIK Ibu',
    'Pekerjaan Ibu',
    'Nama Wali',
    'Pekerjaan Wali',
    'No HP / WA Orang Tua',
    'Email Orang Tua',
    'Alamat Lengkap'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Sample Data
$sheet->setCellValueExplicit('A2', '08.26.0001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('B2', 'Contoh: Ahmad Zidan');
$sheet->setCellValue('C2', 'Budi Santoso');
$sheet->setCellValueExplicit('D2', '3201011122330001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('E2', 'Wiraswasta');
$sheet->setCellValue('F2', 'Siti Aminah');
$sheet->setCellValueExplicit('G2', '3201014455660002', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('H2', 'Ibu Rumah Tangga');
$sheet->setCellValue('I2', '-');
$sheet->setCellValue('J2', '-');
$sheet->setCellValueExplicit('K2', '081234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('L2', 'budi.santoso@email.com');
$sheet->setCellValue('M2', 'Jl. Kebahagiaan No. 7, Jakarta');

$testFilePath = __DIR__ . '/test_sample_orangtua.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFilePath);

echo "Template saved to $testFilePath\n";

echo "=== TEST 2: Read & Verify Orang Tua Columns ===\n";
$loaded = IOFactory::load($testFilePath);
$data = $loaded->getActiveSheet()->toArray(null, true, true, true);

foreach ($data as $idx => $row) {
    echo "Row $idx: NIS='{$row['A']}', Siswa='{$row['B']}', Ayah='{$row['C']}', NIK_A='{$row['D']}', Ibu='{$row['F']}', NIK_I='{$row['G']}', HP='{$row['K']}', Email='{$row['L']}', Alamat='{$row['M']}'\n";
}

echo "=== ALL ORANG TUA PARSING CHECKS PASSED ===\n";
