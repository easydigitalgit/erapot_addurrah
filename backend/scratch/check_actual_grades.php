<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mapel_id = 5; // IPA
$ta_id = 9;    // 2025/2026 Genap
$rombel_id = 15; // I'll assume Zamrud is 15 or check it

$res = $mysqli->query("SELECT id FROM rombel WHERE nama_rombel LIKE '%Zamrud%'");
$rombel = $res->fetch_assoc();
$rombel_id = $rombel['id'];

echo "Checking Nilai Formatif for Rombel ID: $rombel_id, Mapel ID: $mapel_id, TA ID: $ta_id\n";

$sql = "SELECT s.nama_lengkap, f.pertemuan, f.jenis_penilaian, f.nilai_angka, f.kategori 
        FROM nilai_formatif f 
        JOIN siswa s ON s.id = f.siswa_id 
        WHERE f.mapel_id = $mapel_id AND f.tahun_ajaran_id = $ta_id AND f.rombel_id = $rombel_id
        LIMIT 20";

$res = $mysqli->query($sql);
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        echo "- Student: {$row['nama_lengkap']} | P: {$row['pertemuan']} | J: {$row['jenis_penilaian']} | Val: {$row['nilai_angka']} | Kat: {$row['kategori']}\n";
    }
} else {
    echo "No grades found in nilai_formatif for this filter.\n";
}

$mysqli->close();
