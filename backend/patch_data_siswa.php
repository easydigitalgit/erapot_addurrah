<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection using .env
$envContent = file_get_contents('.env');
function getEnvValue($key, $content) {
    if (preg_match('/^' . preg_quote($key) . '\s*=\s*(.*)$/m', $content, $matches)) {
        return trim($matches[1], " \t\n\r\0\x0B'\"");
    }
    return null;
}

$host = getEnvValue('database.default.hostname', $envContent);
$db   = getEnvValue('database.default.database', $envContent);
$user = getEnvValue('database.default.username', $envContent);
$pass = getEnvValue('database.default.password', $envContent);

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$file = '../docs/Data Siswa Dapodik Perkelas.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getSheetByName('daftar peserta didik');

if (!$sheet) {
    die("Sheet 'daftar peserta didik' not found.\n");
}

$data = $sheet->toArray();
$log = [];
$stats = [
    'total_rows' => 0,
    'matched_nisn' => 0,
    'matched_nik' => 0,
    'matched_name' => 0,
    'not_found' => 0,
    'updated' => 0
];

echo "Processing data...\n";

// Start from Index 6 (Row 7)
for ($i = 6; $i < count($data); $i++) {
    $row = $data[$i];
    if (empty(array_filter($row))) continue;

    $stats['total_rows']++;
    
    $excel_name = trim($row[1]);
    $excel_nis  = trim($row[2]);
    $excel_nisn = trim($row[4]);
    $excel_nik  = trim($row[7]);
    
    $student = null;
    $match_method = '';

    // 1. Match by NISN
    if (!empty($excel_nisn)) {
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $stmt->execute([$excel_nisn]);
        $student = $stmt->fetch();
        if ($student) {
            $match_method = 'NISN';
            $stats['matched_nisn']++;
        }
    }

    // 2. Match by NIK
    if (!$student && !empty($excel_nik)) {
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nik = ?");
        $stmt->execute([$excel_nik]);
        $student = $stmt->fetch();
        if ($student) {
            $match_method = 'NIK';
            $stats['matched_nik']++;
        }
    }

    // 3. Match by Name
    if (!$student && !empty($excel_name)) {
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE LOWER(nama_lengkap) = LOWER(?)");
        $stmt->execute([$excel_name]);
        $matches = $stmt->fetchAll();
        if (count($matches) === 1) {
            $student = $matches[0];
            $match_method = 'Name';
            $stats['matched_name']++;
        } elseif (count($matches) > 1) {
            // If multiple names, try to narrow down with NIS or something
            foreach ($matches as $m) {
                if ($m['nis'] == $excel_nis) {
                    $student = $m;
                    $match_method = 'Name + NIS';
                    $stats['matched_name']++;
                    break;
                }
            }
        }
    }

    if ($student) {
        $siswa_id = $student['id'];
        
        // Update Siswa
        $update_siswa = $pdo->prepare("UPDATE siswa SET 
            nis = ?, 
            nisn = ?, 
            nik = ?, 
            nama_lengkap = ?, 
            jenis_kelamin = ?, 
            tempat_lahir = ?, 
            tanggal_lahir = ?, 
            agama = ?, 
            alamat_siswa = ?, 
            rt = ?, 
            rw = ?, 
            dusun = ?, 
            kelurahan = ?, 
            kecamatan = ?, 
            kode_pos = ?, 
            jenis_tinggal = ?, 
            alat_transportasi = ?, 
            no_hp = ?, 
            asal_sekolah = ?, 
            no_kk = ?, 
            berat_badan = ?, 
            tinggi_badan = ?, 
            lingkar_kepala = ?, 
            jml_saudara_kandung = ?, 
            jarak_ke_sekolah = ?
            WHERE id = ?");
        
        $update_siswa->execute([
            $excel_nis,
            $excel_nisn,
            $excel_nik,
            $excel_name,
            $row[3], // JK
            $row[5], // Tempat Lahir
            $row[6], // Tanggal Lahir
            $row[8], // Agama
            $row[9], // Alamat
            $row[10], // RT
            $row[11], // RW
            $row[12], // Dusun
            $row[13], // Kelurahan
            $row[14], // Kecamatan
            $row[15], // Kode Pos
            $row[16], // Jenis Tinggal
            $row[17], // Alat Transportasi
            $row[19], // HP
            $row[56], // Sekolah Asal (Adjusted index)
            $row[60], // No KK (Adjusted index)
            (int)$row[61], // Berat
            (int)$row[62], // Tinggi
            (int)$row[63], // Lingkar Kepala
            (int)$row[64], // Saudara
            $row[65], // Jarak
            $siswa_id
        ]);

        // Update Parent Info
        $stmt = $pdo->prepare("SELECT id FROM orangtua_wali WHERE siswa_id = ?");
        $stmt->execute([$siswa_id]);
        $parent = $stmt->fetch();
        
        if ($parent) {
            $update_parent = $pdo->prepare("UPDATE orangtua_wali SET 
                nama_ayah = ?, tahun_lahir_ayah = ?, pendidikan_ayah = ?, pekerjaan_ayah = ?, penghasilan_ayah = ?, nik_ayah = ?,
                nama_ibu = ?, tahun_lahir_ibu = ?, pendidikan_ibu = ?, pekerjaan_ibu = ?, penghasilan_ibu = ?, nik_ibu = ?,
                nama_wali = ?, tahun_lahir_wali = ?, pendidikan_wali = ?, pekerjaan_wali = ?, penghasilan_wali = ?, nik_wali = ?,
                no_hp_ortu = ?
                WHERE siswa_id = ?");
            $update_parent->execute([
                $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                $row[30], $row[31], $row[32], $row[33], $row[34], $row[35],
                $row[36], $row[37], $row[38], $row[39], $row[40], $row[41],
                $row[19], // Use HP from student data as default parent HP if needed, or row[18]/[19]
                $siswa_id
            ]);
        } else {
            $insert_parent = $pdo->prepare("INSERT INTO orangtua_wali (
                siswa_id, nama_ayah, tahun_lahir_ayah, pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah, nik_ayah,
                nama_ibu, tahun_lahir_ibu, pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu, nik_ibu,
                nama_wali, tahun_lahir_wali, pendidikan_wali, pekerjaan_wali, penghasilan_wali, nik_wali,
                no_hp_ortu
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_parent->execute([
                $siswa_id, $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                $row[30], $row[31], $row[32], $row[33], $row[34], $row[35],
                $row[36], $row[37], $row[38], $row[39], $row[40], $row[41],
                $row[19]
            ]);
        }

        $stats['updated']++;
        $log[] = [
            'status' => 'UPDATED',
            'match' => $match_method,
            'name' => $excel_name,
            'nis_old' => $student['nis'],
            'nis_new' => $excel_nis,
            'id' => $siswa_id
        ];
    } else {
        $stats['not_found']++;
        $log[] = [
            'status' => 'NOT_FOUND',
            'name' => $excel_name,
            'nis' => $excel_nis,
            'nisn' => $excel_nisn
        ];
    }
}

// Generate report
$report = "# Laporan Perbaikan Data Siswa Dapodik\n\n";
$report .= "Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
$report .= "## Statistik\n";
$report .= "- Total Baris di Excel: " . $stats['total_rows'] . "\n";
$report .= "- Berhasil Dicocokkan via NISN: " . $stats['matched_nisn'] . "\n";
$report .= "- Berhasil Dicocokkan via NIK: " . $stats['matched_nik'] . "\n";
$report .= "- Berhasil Dicocokkan via Nama: " . $stats['matched_name'] . "\n";
$report .= "- Tidak Ditemukan di Database: " . $stats['not_found'] . "\n";
$report .= "- Total Terupdate: " . $stats['updated'] . "\n\n";

$report .= "## Detail Perbaikan (Duplikasi NIS Teratasi)\n";
$report .= "| Nama | NIS Lama | NIS Baru (Dapodik) | Status |\n";
$report .= "| --- | --- | --- | --- |\n";

foreach ($log as $item) {
    if ($item['status'] === 'UPDATED') {
        $report .= "| " . $item['name'] . " | " . $item['nis_old'] . " | " . $item['nis_new'] . " | UPDATED (" . $item['match'] . ") |\n";
    }
}

$report .= "\n## Siswa Tidak Ditemukan\n";
$report .= "| Nama | NIS Excel | NISN Excel |\n";
$report .= "| --- | --- | --- |\n";
foreach ($log as $item) {
    if ($item['status'] === 'NOT_FOUND') {
        $report .= "| " . $item['name'] . " | " . $item['nis'] . " | " . $item['nisn'] . " |\n";
    }
}

file_put_contents('../docs/perbaikan_data_siswa_dapodik.md', $report);
echo "Report generated in docs/perbaikan_data_siswa_dapodik.md\n";
echo "Total updated: " . $stats['updated'] . "\n";
