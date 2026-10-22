<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');

$restoredIds = [
    540, 550, 551, 451, 453, 461, 439, 440, 454, 629, 630, 6, 29, 36, 39, 
    40, 46, 51, 52, 54, 68, 74, 91, 94, 99, 164, 166, 176, 187, 137, 
    208, 209, 211, 222, 233, 243, 246, 256, 267
];

$inClause = implode(',', $restoredIds);
$stmt = $pdo->query("SELECT id, nama_lengkap, nisn, status_siswa FROM siswa WHERE id IN ($inClause)");
$foundSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total siswa dipulihkan di tabel siswa: " . count($foundSiswa) . " / " . count($restoredIds) . "\n";

$foundIds = array_column($foundSiswa, 'id');
$missing = array_diff($restoredIds, $foundIds);
if (!empty($missing)) {
    echo "ID siswa belum ada di DB lokal: " . implode(', ', $missing) . "\n";
} else {
    echo "SEMUA 39 SISWA LENGKAP DI DB LOKAL!\n";
}

// Cek orangtua_wali
$stmt = $pdo->query("SELECT siswa_id, COUNT(*) as jml FROM orangtua_wali WHERE siswa_id IN ($inClause) GROUP BY siswa_id");
$foundOrtu = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
echo "Total orangtua_wali di DB lokal: " . count($foundOrtu) . " / " . count($restoredIds) . "\n";

// Cek apakah ada duplikat di orangtua_wali
$duplikatOrtu = array_filter($foundOrtu, fn($jml) => $jml > 1);
if (!empty($duplikatOrtu)) {
    echo "Ada siswa_id dengan record orangtua_wali ganda: " . implode(', ', array_keys($duplikatOrtu)) . "\n";
}
