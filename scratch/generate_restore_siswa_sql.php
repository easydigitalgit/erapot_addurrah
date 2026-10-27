<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$missingIds = [4,9,11,12,24,40,44,72,75,77,114,118,119,120,121,122,123,124,125,440,444,446,534,540];
$inStr = implode(',', $missingIds);

$students = $pdo->query("SELECT * FROM raporsmpit.siswa WHERE id IN ($inStr) ORDER BY id")->fetchAll();

$sql = "-- ==========================================================================\n";
$sql .= "-- STEP 1: RESTORE 24 SISWA HILANG KE TABEL `siswa`\n";
$sql .= "-- Mengembalikan 24 master record siswa yang terhapus di VPS\n";
$sql .= "-- Tanggal: 27 Oktober 2026\n";
$sql .= "-- ==========================================================================\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($students as $s) {
    $cols = array_keys($s);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $vals = array_map(function($v) use ($pdo) {
        if ($v === null) return "NULL";
        return $pdo->quote($v);
    }, array_values($s));
    
    $sql .= "-- Siswa ID {$s['id']} ({$s['nama_lengkap']} - NIS {$s['nis']})\n";
    $sql .= "INSERT INTO `siswa` (" . implode(', ', $escapedCols) . ")\nVALUES (" . implode(', ', $vals) . ")\n";
    $sql .= "ON DUPLICATE KEY UPDATE `rombel_id` = VALUES(`rombel_id`), `status_siswa` = VALUES(`status_siswa`), `nama_lengkap` = VALUES(`nama_lengkap`);\n\n";
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('docs/dbrepair/01_restore_24_siswa.sql', $sql);
echo "File docs/dbrepair/01_restore_24_siswa.sql created with " . count($students) . " students.\n";

