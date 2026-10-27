<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$missingSiswaIds = [4,9,11,12,24,40,44,72,75,77,114,118,119,120,121,122,123,124,125,440,444,446,534,540];
$idsStr = implode(',', $missingSiswaIds);

$sumatifOfDeleted = $pdo->query("SELECT COUNT(*) FROM raporsmpit.nilai_sumatif WHERE siswa_id IN ($idsStr)")->fetchColumn();
echo "Jumlah nilai_sumatif di Lokal milik 24 siswa yang terhapus di VPS: $sumatifOfDeleted\n";

// Cek absensi_harian milik 24 siswa
$absensiOfDeleted = $pdo->query("SELECT COUNT(*) FROM raporsmpit.absensi_harian WHERE siswa_id IN ($idsStr)")->fetchColumn();
echo "Jumlah absensi_harian di Lokal milik 24 siswa yang terhapus di VPS: $absensiOfDeleted\n";

// Cek nilai_ekskul milik 24 siswa
$ekskulOfDeleted = $pdo->query("SELECT COUNT(*) FROM raporsmpit.nilai_ekskul WHERE siswa_id IN ($idsStr)")->fetchColumn();
echo "Jumlah nilai_ekskul di Lokal milik 24 siswa yang terhapus di VPS: $ekskulOfDeleted\n";

// Total selisih:
// Sumatif: 15263 - 13679 = 1584. Dan nilai sumatif milik 24 siswa = ?

