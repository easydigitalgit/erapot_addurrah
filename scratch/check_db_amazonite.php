<?php
require 'backend/vendor/autoload.php';

// Load CodeIgniter framework or directly PDO
$dbConfig = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => '',
    'db'   => 'erapot' // let's find the db name from .env
];

$envFile = 'backend/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, 'database.default.database') !== false) {
            $parts = explode('=', $line);
            $dbConfig['db'] = trim($parts[1]);
        }
        if (strpos($line, 'database.default.username') !== false) {
            $parts = explode('=', $line);
            $dbConfig['user'] = trim($parts[1]);
        }
        if (strpos($line, 'database.default.password') !== false) {
            $parts = explode('=', $line);
            $dbConfig['pass'] = trim($parts[1]);
        }
    }
}

$pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset=utf8mb4", $dbConfig['user'], $dbConfig['pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Connected to DB: {$dbConfig['db']}\n\n";

// Check TA 10
$stmt = $pdo->query("SELECT * FROM tahun_ajaran WHERE id = 10");
$ta = $stmt->fetch(PDO::FETCH_ASSOC);
echo "=== TAHUN AJARAN 10 ===\n";
print_r($ta);

// Check Rombel 77
$stmt = $pdo->query("SELECT * FROM rombel WHERE id = 77");
$rombel = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\n=== ROMBEL 77 ===\n";
print_r($rombel);

// Check Mapel 6
$stmt = $pdo->query("SELECT * FROM mata_pelajaran WHERE id = 6");
$mapel = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\n=== MAPEL 6 ===\n";
print_r($mapel);

// Check Master LM for Mapel 6
$tingkat = $rombel['tingkat'] ?? '';
echo "\n=== MASTER LM for mapel_id=6, tingkat=$tingkat ===\n";
$stmt = $pdo->prepare("SELECT * FROM master_lm WHERE mapel_id = 6");
$stmt->execute();
$lms = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($lms as $lm) {
    echo "ID: {$lm['id']}, Kode: {$lm['kode_lm']}, Tingkat: {$lm['tingkat']}, Semester: {$lm['semester']}, Kategori: " . ($lm['kategori'] ?? '') . ", Materi: " . substr($lm['deskripsi_lm'] ?? '', 0, 40) . "\n";
}

// Check existing nilai_formatif for rombel 77 and mapel 6
echo "\n=== NILAI FORMATIF for rombel_id=77, mapel_id=6 ===\n";
$stmt = $pdo->prepare("SELECT COUNT(*) as cnt, jenis_penilaian, pertemuan, kategori FROM nilai_formatif WHERE rombel_id = 77 AND mapel_id = 6 GROUP BY jenis_penilaian, pertemuan, kategori");
$stmt->execute();
$nfc = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($nfc);

// Check sample student (e.g. Row 10: Al Buqhori Rafif, NIS 13.26.00024)
$stmt = $pdo->prepare("SELECT id, nis, nama_lengkap FROM siswa WHERE nis = '13.26.00024'");
$stmt->execute();
$s1 = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\n=== SISWA 1 (Al Buqhori Rafif) ===\n";
print_r($s1);
if ($s1) {
    $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = 6");
    $stmt->execute([$s1['id']]);
    echo "Nilai Formatif:\n";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = 6");
    $stmt->execute([$s1['id']]);
    echo "Nilai Sumatif:\n";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
