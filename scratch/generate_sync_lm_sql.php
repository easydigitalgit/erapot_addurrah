<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// 1. Ambil 54 LM yang ada di lokal tapi tidak ada di VPS
$missingInVps = $pdo->query("
    SELECT l.* 
    FROM raporsmpit.master_lm l
    LEFT JOIN raporsmpit_vps.master_lm v ON l.id = v.id
    WHERE v.id IS NULL
    ORDER BY l.tingkat, l.semester, l.kategori, l.mapel_id, l.kode_lm
")->fetchAll();

// 2. Ambil 3 LM baru yang ada di VPS tapi belum ada di lokal
$missingInLocal = $pdo->query("
    SELECT v.* 
    FROM raporsmpit_vps.master_lm v
    LEFT JOIN raporsmpit.master_lm l ON v.id = l.id
    WHERE l.id IS NULL
    ORDER BY v.id
")->fetchAll();

$sql = "-- ==========================================================================\n";
$sql .= "-- STEP 3: SINKRONISASI & RESTORE MASTER LINGKUP MATERI (LM)\n";
$sql .= "-- 1. Mengembalikan 54 LM Tingkat 8 & 9 yang hilang di VPS\n";
$sql .= "-- 2. Mempertahankan 3 LM baru inputan guru di VPS (B. Inggris Tingkat 9)\n";
$sql .= "-- Tanggal: 27 Oktober 2026\n";
$sql .= "-- ==========================================================================\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

$sql .= "-- BAGIAN A: RESTORE 54 LM KE DATABASE VPS\n";
foreach ($missingInVps as $lm) {
    $cols = array_keys($lm);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $vals = array_map(function($v) use ($pdo) {
        if ($v === null) return "NULL";
        return $pdo->quote($v);
    }, array_values($lm));
    
    $sql .= "-- LM ID {$lm['id']} (Mapel ID {$lm['mapel_id']}, Tgt {$lm['tingkat']}, {$lm['semester']} {$lm['kategori']}, {$lm['kode_lm']})\n";
    $sql .= "INSERT INTO `master_lm` (" . implode(', ', $escapedCols) . ")\nVALUES (" . implode(', ', $vals) . ")\n";
    $sql .= "ON DUPLICATE KEY UPDATE `deskripsi_lm` = VALUES(`deskripsi_lm`), `status` = VALUES(`status`);\n\n";
}

$sql .= "\n-- BAGIAN B: SINKRONISASI 3 LM BARU DARI VPS KE DATABASE LOKAL (JIKA MENERAPKAN KE LOKAL)\n";
foreach ($missingInLocal as $lm) {
    $cols = array_keys($lm);
    $escapedCols = array_map(fn($c) => "`$c`", $cols);
    $vals = array_map(function($v) use ($pdo) {
        if ($v === null) return "NULL";
        return $pdo->quote($v);
    }, array_values($lm));
    
    $sql .= "-- LM ID {$lm['id']} (Mapel ID {$lm['mapel_id']}, Tgt {$lm['tingkat']}, {$lm['semester']} {$lm['kategori']}, {$lm['kode_lm']})\n";
    $sql .= "INSERT INTO `master_lm` (" . implode(', ', $escapedCols) . ")\nVALUES (" . implode(', ', $vals) . ")\n";
    $sql .= "ON DUPLICATE KEY UPDATE `deskripsi_lm` = VALUES(`deskripsi_lm`), `status` = VALUES(`status`);\n\n";
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('docs/dbrepair/03_sinkronisasi_master_lm.sql', $sql);
echo "File docs/dbrepair/03_sinkronisasi_master_lm.sql created with 54 restore LM and 3 new LM.\n";

