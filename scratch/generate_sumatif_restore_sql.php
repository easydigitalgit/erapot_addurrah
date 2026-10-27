<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// 1. Ambil semua nilai_sumatif di lokal yang TIDAK ADA di VPS
$missingInVps = $pdo->query("
    SELECT l.* 
    FROM raporsmpit.nilai_sumatif l
    LEFT JOIN raporsmpit_vps.nilai_sumatif v ON l.id = v.id
    WHERE v.id IS NULL
    ORDER BY l.tahun_ajaran_id, l.mapel_id, l.siswa_id, l.id
")->fetchAll();

echo "Missing sumatif in VPS: " . count($missingInVps) . " rows\n";

// 2. Ambil semua nilai_sumatif di VPS yang TIDAK ADA di lokal (inputan baru guru)
$missingInLocal = $pdo->query("
    SELECT v.* 
    FROM raporsmpit_vps.nilai_sumatif v
    LEFT JOIN raporsmpit.nilai_sumatif l ON v.id = l.id
    WHERE l.id IS NULL
    ORDER BY v.tahun_ajaran_id, v.mapel_id, v.siswa_id, v.id
")->fetchAll();

echo "New sumatif in VPS (missing in local): " . count($missingInLocal) . " rows\n";

$sql = "-- ==========================================================================\n";
$sql .= "-- STEP 4: RESTORE & MERGE NILAI SUMATIF (1.584 RESTORE + NEW INPUT PRESERVE)\n";
$sql .= "-- Mengembalikan nilai sumatif yang musnah akibat cascade delete siswa\n";
$sql .= "-- Tanggal: 27 Oktober 2026\n";
$sql .= "-- ==========================================================================\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

$sql .= "-- RESTORE 1.584 NILAI SUMATIF KE VPS (MENGGUNAKAN INSERT IGNORE AGAR AMAN)\n";
$chunks = array_chunk($missingInVps, 100);
foreach ($chunks as $chunk) {
    $cols = array_keys($chunk[0]);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $valLines = [];
    foreach ($chunk as $row) {
        $vals = array_map(function($v) use ($pdo) {
            if ($v === null) return "NULL";
            return $pdo->quote($v);
        }, array_values($row));
        $valLines[] = "(" . implode(', ', $vals) . ")";
    }
    $sql .= "INSERT IGNORE INTO `nilai_sumatif` (" . implode(', ', $escapedCols) . ") VALUES\n" . implode(",\n", $valLines) . ";\n\n";
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql', $sql);
echo "File docs/dbrepair/04_restore_nilai_sumatif_terhapus.sql created.\n";

// Buat juga script sebaliknya: sinkronisasi nilai baru dari VPS ke database lokal
$sqlSync = "-- ==========================================================================\n";
$sqlSync .= "-- STEP 5: SINKRONISASI NILAI BARU INPUTAN GURU VPS (27 OKTOBER) KE DATABASE LOKAL\n";
$sqlSync .= "-- Menyerap nilai formatif & sumatif baru guru di VPS ke database lokal\n";
$sqlSync .= "-- Tanggal: 27 Oktober 2026\n";
$sqlSync .= "-- ==========================================================================\n\n";
$sqlSync .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

$sqlSync .= "-- A. SINKRONISASI NILAI SUMATIF BARU DARI VPS (" . count($missingInLocal) . " ROWS)\n";
$chunks = array_chunk($missingInLocal, 100);
foreach ($chunks as $chunk) {
    $cols = array_keys($chunk[0]);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $valLines = [];
    foreach ($chunk as $row) {
        $vals = array_map(function($v) use ($pdo) {
            if ($v === null) return "NULL";
            return $pdo->quote($v);
        }, array_values($row));
        $valLines[] = "(" . implode(', ', $vals) . ")";
    }
    $sqlSync .= "INSERT IGNORE INTO `nilai_sumatif` (" . implode(', ', $escapedCols) . ") VALUES\n" . implode(",\n", $valLines) . ";\n\n";
}

// Formatif baru dari VPS yang belum ada di lokal
$missingFormatifInLocal = $pdo->query("
    SELECT v.* 
    FROM raporsmpit_vps.nilai_formatif v
    LEFT JOIN raporsmpit.nilai_formatif l ON v.id = l.id
    WHERE l.id IS NULL
    ORDER BY v.id
")->fetchAll();

echo "New formatif in VPS (missing in local): " . count($missingFormatifInLocal) . " rows\n";

$sqlSync .= "-- B. SINKRONISASI NILAI FORMATIF BARU DARI VPS (" . count($missingFormatifInLocal) . " ROWS)\n";
$chunks = array_chunk($missingFormatifInLocal, 100);
foreach ($chunks as $chunk) {
    $cols = array_keys($chunk[0]);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $valLines = [];
    foreach ($chunk as $row) {
        $vals = array_map(function($v) use ($pdo) {
            if ($v === null) return "NULL";
            return $pdo->quote($v);
        }, array_values($row));
        $valLines[] = "(" . implode(', ', $vals) . ")";
    }
    $sqlSync .= "INSERT IGNORE INTO `nilai_formatif` (" . implode(', ', $escapedCols) . ") VALUES\n" . implode(",\n", $valLines) . ";\n\n";
}

$sqlSync .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql', $sqlSync);
echo "File docs/dbrepair/05_sinkronisasi_nilai_guru_vps_ke_lokal.sql created.\n";
