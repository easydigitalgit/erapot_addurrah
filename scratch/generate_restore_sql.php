<?php
require 'backend/vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');
$sheet = $spreadsheet->getSheetByName('VIII');

$map = [
    131 => ['id' => 112, 'nis' => '09.25.0012'],
    132 => ['id' => 113, 'nis' => '09.25.0015'],
    135 => ['id' => 88,  'nis' => '09.25.0019'],
    139 => ['id' => 119, 'nis' => '09.25.0041'],
    141 => ['id' => 121, 'nis' => '09.25.0055'],
    150 => ['id' => 128, 'nis' => '09.25.0111'],
    151 => ['id' => 130, 'nis' => '09.25.0122']
];

$sqlSiswa = [];
$sqlOrtu = [];

foreach ($map as $rowNum => $meta) {
    $id = $meta['id'];
    $nis = $meta['nis'];
    $nama = addslashes(trim($sheet->getCell('B' . $rowNum)->getValue()));
    $nisn = trim((string)$sheet->getCell('D' . $rowNum)->getValue());
    $tempat_lahir = addslashes(trim((string)$sheet->getCell('E' . $rowNum)->getValue()));
    
    $tglRaw = $sheet->getCell('F' . $rowNum)->getValue();
    if (is_numeric($tglRaw)) {
        $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglRaw)->format('Y-m-d');
    } else {
        $tgl_lahir = date('Y-m-d', strtotime($tglRaw));
    }
    
    $nik = trim((string)$sheet->getCell('G' . $rowNum)->getValue());
    $alamat = addslashes(trim((string)$sheet->getCell('H' . $rowNum)->getValue()));
    $dusun = addslashes(trim((string)$sheet->getCell('I' . $rowNum)->getValue()));
    $kelurahan = addslashes(trim((string)$sheet->getCell('J' . $rowNum)->getValue()));
    $kecamatan = addslashes(trim((string)$sheet->getCell('K' . $rowNum)->getValue()));
    $kode_pos = trim((string)$sheet->getCell('L' . $rowNum)->getValue());
    $jenis_tinggal = addslashes(trim((string)$sheet->getCell('M' . $rowNum)->getValue()));
    $transportasi = addslashes(trim((string)$sheet->getCell('N' . $rowNum)->getValue()));
    $hp = trim((string)$sheet->getCell('O' . $rowNum)->getValue());
    
    $asal_sekolah = addslashes(trim((string)$sheet->getCell('AI' . $rowNum)->getValue()));
    $anak_ke = (int)$sheet->getCell('AJ' . $rowNum)->getValue() ?: 'NULL';
    $jml_saudara = (int)$sheet->getCell('AK' . $rowNum)->getValue() ?: 'NULL';
    
    $jk = 'P';
    $rombel_id = 76;
    
    $sqlSiswa[] = "($id, $rombel_id, '$nis', '$nisn', '$nik', '$nama', '$jk', '$tempat_lahir', '$tgl_lahir', 'Islam', '$alamat', '$dusun', '$kelurahan', '$kecamatan', '$kode_pos', '$jenis_tinggal', '$transportasi', '$hp', '$asal_sekolah', 'Aktif', $anak_ke, $jml_saudara)";
    
    // Ortu
    $nama_ayah = addslashes(trim((string)$sheet->getCell('P' . $rowNum)->getValue()));
    $thn_ayah = trim((string)$sheet->getCell('Q' . $rowNum)->getValue());
    $pend_ayah = addslashes(trim((string)$sheet->getCell('R' . $rowNum)->getValue()));
    $pek_ayah = addslashes(trim((string)$sheet->getCell('S' . $rowNum)->getValue()));
    $gaji_ayah = addslashes(trim((string)$sheet->getCell('T' . $rowNum)->getValue()));
    $nik_ayah = trim((string)$sheet->getCell('U' . $rowNum)->getValue());
    
    $nama_ibu = addslashes(trim((string)$sheet->getCell('V' . $rowNum)->getValue()));
    $thn_ibu = trim((string)$sheet->getCell('W' . $rowNum)->getValue());
    $pend_ibu = addslashes(trim((string)$sheet->getCell('X' . $rowNum)->getValue()));
    $pek_ibu = addslashes(trim((string)$sheet->getCell('Y' . $rowNum)->getValue()));
    $gaji_ibu = addslashes(trim((string)$sheet->getCell('Z' . $rowNum)->getValue()));
    $nik_ibu = trim((string)$sheet->getCell('AA' . $rowNum)->getValue());

    $nama_wali = addslashes(trim((string)$sheet->getCell('AB' . $rowNum)->getValue()));
    $thn_wali = trim((string)$sheet->getCell('AC' . $rowNum)->getValue());
    $pend_wali = addslashes(trim((string)$sheet->getCell('AD' . $rowNum)->getValue()));
    $pek_wali = addslashes(trim((string)$sheet->getCell('AE' . $rowNum)->getValue()));
    $gaji_wali = addslashes(trim((string)$sheet->getCell('AF' . $rowNum)->getValue()));
    $nik_wali = trim((string)$sheet->getCell('AG' . $rowNum)->getValue());

    $sqlOrtu[] = "($id, '$nama_ayah', '$thn_ayah', '$pend_ayah', '$pek_ayah', '$gaji_ayah', '$nik_ayah', '$nama_ibu', '$thn_ibu', '$pend_ibu', '$pek_ibu', '$gaji_ibu', '$nik_ibu', '$nama_wali', '$thn_wali', '$pend_wali', '$pek_wali', '$gaji_wali', '$nik_wali')";
}

$querySiswa = "INSERT INTO siswa (id, rombel_id, nis, nisn, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, alamat_siswa, dusun, kelurahan, kecamatan, kode_pos, jenis_tinggal, alat_transportasi, no_hp, asal_sekolah, status_siswa, anak_ke, jml_saudara_kandung) VALUES\n" . implode(",\n", $sqlSiswa) . ";\n";

$queryOrtu = "INSERT INTO orangtua_wali (siswa_id, nama_ayah, tahun_lahir_ayah, pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah, nik_ayah, nama_ibu, tahun_lahir_ibu, pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu, nik_ibu, nama_wali, tahun_lahir_wali, pendidikan_wali, pekerjaan_wali, penghasilan_wali, nik_wali) VALUES\n" . implode(",\n", $sqlOrtu) . ";\n";

echo "=== QUERY RESTORE SISWA ===\n";
echo $querySiswa . "\n";

echo "=== QUERY RESTORE ORANGTUA_WALI ===\n";
echo $queryOrtu . "\n";

// Execute locally
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
if ($m->query($querySiswa)) {
    echo "BERHASIL RESTORE SISWA KE DATABASE LOKAL!\n";
} else {
    echo "GAGAL RESTORE SISWA: " . $m->error . "\n";
}

if ($m->query($queryOrtu)) {
    echo "BERHASIL RESTORE ORANGTUA_WALI KE DATABASE LOKAL!\n";
} else {
    echo "GAGAL RESTORE ORANGTUA_WALI: " . $m->error . "\n";
}
