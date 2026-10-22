<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== ANALISIS RELASI DATA UNTUK SISWA ID 581 - 620 ===\n";

$res = $mysqli->query("SELECT id, user_id, nis, nisn, nama_lengkap FROM siswa WHERE id BETWEEN 581 AND 620 ORDER BY id ASC");
$students = [];
while ($r = $res->fetch_assoc()) {
    $students[] = $r;
}

echo "Total siswa di range 581-620: " . count($students) . "\n\n";

$hasRelCount = 0;
foreach ($students as $s) {
    $sid = $s['id'];
    $uid = (int)$s['user_id'];
    $relations = [];
    
    // Check anggota_rombel
    $ar = $mysqli->query("SELECT COUNT(*) as c FROM anggota_rombel WHERE siswa_id = $sid")->fetch_assoc()['c'];
    if ($ar > 0) $relations[] = "anggota_rombel: $ar";
    
    // Check nf
    $nf = $mysqli->query("SELECT COUNT(*) as c FROM nilai_formatif WHERE siswa_id = $sid")->fetch_assoc()['c'];
    if ($nf > 0) $relations[] = "nilai_formatif: $nf";

    // Check ns
    $ns = $mysqli->query("SELECT COUNT(*) as c FROM nilai_sumatif WHERE siswa_id = $sid")->fetch_assoc()['c'];
    if ($ns > 0) $relations[] = "nilai_sumatif: $ns";

    // Check nr
    $nr = $mysqli->query("SELECT COUNT(*) as c FROM nilai_rapor WHERE siswa_id = $sid")->fetch_assoc()['c'];
    if ($nr > 0) $relations[] = "nilai_rapor: $nr";

    // Check ortu
    $ot = $mysqli->query("SELECT COUNT(*) as c FROM orangtua_wali WHERE siswa_id = $sid")->fetch_assoc()['c'];
    if ($ot > 0) $relations[] = "orangtua_wali: $ot";

    // Check if user exists
    $u = $mysqli->query("SELECT id, username FROM users WHERE id = $uid")->fetch_assoc();
    $uStr = $u ? "User #{$u['id']} ({$u['username']})" : "No User";

    // Check duplicate student by matching the name that was shifted into NISN column
    $cleanName = $mysqli->real_escape_string(trim($s['nisn']));
    $dup = $mysqli->query("SELECT id, nis, nama_lengkap, rombel_id FROM siswa WHERE nama_lengkap LIKE '%$cleanName%' AND id NOT BETWEEN 581 AND 620 LIMIT 3");
    $dupStr = [];
    while ($d = $dup->fetch_assoc()) {
        $dupStr[] = "Match ID {$d['id']} ({$d['nama_lengkap']}, NIS: {$d['nis']}, Rombel: {$d['rombel_id']})";
    }

    if (!empty($relations)) $hasRelCount++;

    echo "ID $sid | NIS: {$s['nis']} | NISN (Nama): {$s['nisn']} | Nama (JK): {$s['nama_lengkap']}\n";
    echo "  $uStr\n";
    echo "  Relations: " . (empty($relations) ? "TIDAK ADA (0 relasi)" : implode(', ', $relations)) . "\n";
    echo "  Existing original student: " . (empty($dupStr) ? "TIDAK DITEMUKAN" : implode('; ', $dupStr)) . "\n";
    echo "\n";
}

echo "Total siswa yang punya relasi akademik/rombel di 581-620: $hasRelCount\n";
