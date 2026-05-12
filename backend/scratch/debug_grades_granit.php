<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

$conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Get TA ID for 2025/2026 Genap
$res = $conn->query("SELECT id FROM tahun_ajaran WHERE tahun = '2025/2026' AND semester = 'Genap'");
$ta = $res->fetch_assoc();
$ta_id = $ta['id'] ?? null;
echo "TA ID: $ta_id\n";

// 2. Get Rombel ID for Granit
$res = $conn->query("SELECT id FROM rombel WHERE nama_rombel = 'Granit'");
$rombel = $res->fetch_assoc();
$rombel_id = $rombel['id'] ?? null;
echo "Rombel ID: $rombel_id\n";

// 3. Get Mapel ID for Bahasa Inggris
$res = $conn->query("SELECT id FROM mata_pelajaran WHERE nama_mapel LIKE '%Bahasa Inggris%'");
$mapel = $res->fetch_assoc();
$mapel_id = $mapel['id'] ?? null;
echo "Mapel ID: $mapel_id\n";

if (!$ta_id || !$rombel_id || !$mapel_id) {
    die("Missing filter IDs\n");
}

// 4. Check Nilai Formatif for Abid Zuhair (NISN: 0131390006)
$res = $conn->query("SELECT id FROM siswa WHERE nisn = '0131390006'");
$siswa = $res->fetch_assoc();
$siswa_id = $siswa['id'] ?? null;
echo "Siswa ID (Abid): $siswa_id\n";

echo "\n--- Nilai Formatif Abid ---\n";
$res = $conn->query("SELECT * FROM nilai_formatif WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id AND rombel_id = $rombel_id");
$sum_nh = 0;
$sum_uh = 0;
while($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']}, Jenis: {$row['jenis_penilaian']}, Pert: {$row['pertemuan']}, Nilai: {$row['nilai_angka']}, Kat: " . ($row['kategori'] ?? 'NULL') . ", Sem: " . ($row['semester'] ?? 'NULL') . "\n";
    $jenis = strtoupper(trim($row['jenis_penilaian'] ?? ''));
    $nilai = (float)$row['nilai_angka'];
    if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
        $sum_uh += $nilai;
    } else {
        $sum_nh += $nilai;
    }
}
echo "Total Sum NH: $sum_nh\n";
echo "Total Sum UH: $sum_uh\n";

echo "\n--- Max Pertemuan in Class (to calculate divisor) ---\n";
$res = $conn->query("SELECT jenis_penilaian, MAX(pertemuan) as max_pert FROM nilai_formatif WHERE mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id AND rombel_id = $rombel_id AND nilai_angka > 0 GROUP BY jenis_penilaian");
$max_nh_pert = 0;
$max_uh_pert = 0;
while($row = $res->fetch_assoc()) {
    echo "Jenis: {$row['jenis_penilaian']}, Max Pert: {$row['max_pert']}\n";
    $jenis = strtoupper(trim($row['jenis_penilaian'] ?? ''));
    $pert = (int)$row['max_pert'];
    if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
        if ($pert > $max_uh_pert) $max_uh_pert = $pert;
    } else {
        if ($pert > $max_nh_pert) $max_nh_pert = $pert;
    }
}
echo "Divisor NH: $max_nh_pert\n";
echo "Divisor UH: $max_uh_pert\n";

if ($max_nh_pert > 0) echo "Avg NH Calculation: $sum_nh / $max_nh_pert = " . ($sum_nh / $max_nh_pert) . "\n";
if ($max_uh_pert > 0) echo "Avg UH Calculation: $sum_uh / $max_uh_pert = " . ($sum_uh / $max_uh_pert) . "\n";

$conn->close();
