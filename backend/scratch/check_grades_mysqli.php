<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mapel_nama = "Ilmu Pengetahuan Alam";
$res = $mysqli->query("SELECT id, nama_mapel FROM mata_pelajaran WHERE nama_mapel LIKE '%$mapel_nama%'");
$mapel = $res->fetch_assoc();

$res = $mysqli->query("SELECT id, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif'");
$ta = $res->fetch_assoc();

if (!$mapel || !$ta) {
    echo "Mapel or TA not found\n";
    exit;
}

echo "Mapel ID: " . $mapel['id'] . " (" . $mapel['nama_mapel'] . ")\n";
echo "TA ID: " . $ta['id'] . " (" . $ta['tahun'] . " " . $ta['semester'] . ")\n";

$res = $mysqli->query("SELECT id, nama_rombel FROM rombel WHERE nama_rombel LIKE '%Zamrud%'");
$rombel_zamrud = $res->fetch_assoc();
echo "Zamrud Rombel ID: " . ($rombel_zamrud['id'] ?? 'Not found') . "\n";

echo "\n--- Formatif Stats per Class and Meeting ---\n";
$sql = "SELECT r.nama_rombel, f.pertemuan, f.jenis_penilaian, COUNT(*) as count 
        FROM nilai_formatif f 
        JOIN rombel r ON r.id = f.rombel_id 
        WHERE f.mapel_id = {$mapel['id']} AND f.tahun_ajaran_id = {$ta['id']} 
        GROUP BY f.rombel_id, f.pertemuan, f.jenis_penilaian";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "Class: " . $row['nama_rombel'] . " | Pertemuan: " . $row['pertemuan'] . " | Jenis: " . $row['jenis_penilaian'] . " | Count: " . $row['count'] . "\n";
}

echo "\n--- Global Max Pertemuan for this Mapel/TA ---\n";
$sql = "SELECT MAX(pertemuan) as max_p FROM nilai_formatif WHERE mapel_id = {$mapel['id']} AND tahun_ajaran_id = {$ta['id']}";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();
echo "Global Max Pertemuan: " . ($row['max_p'] ?? 0) . "\n";

$mysqli->close();
