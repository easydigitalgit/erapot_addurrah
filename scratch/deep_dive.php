<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== 1. DETAIL 3 LM YANG ADA DI VPS TAPI TIDAK ADA DI LOKAL ===\n";
$lmVpsOnly = $pdo->query("
    SELECT v.id, v.tahun_ajaran_id, v.mapel_id, m.nama_mapel, v.tingkat, v.semester, v.kategori, v.kode_lm, v.deskripsi_lm, v.created_at, v.updated_at
    FROM raporsmpit_vps.master_lm v
    LEFT JOIN raporsmpit_vps.mata_pelajaran m ON v.mapel_id = m.id
    LEFT JOIN raporsmpit.master_lm l ON v.id = l.id
    WHERE l.id IS NULL
")->fetchAll();
foreach ($lmVpsOnly as $lm) {
    echo "ID {$lm['id']} | Mapel: {$lm['nama_mapel']} (ID {$lm['mapel_id']}) | Tgt {$lm['tingkat']} | {$lm['semester']} | {$lm['kategori']} | {$lm['kode_lm']} | '{$lm['deskripsi_lm']}' | Updated: {$lm['updated_at']}\n";
}

echo "\n=== 2. DETAIL 54 LM YANG ADA DI LOKAL TAPI HILANG DI VPS ===\n";
$lmLocalOnly = $pdo->query("
    SELECT l.id, l.tahun_ajaran_id, l.mapel_id, m.nama_mapel, l.tingkat, l.semester, l.kategori, l.kode_lm, l.deskripsi_lm, l.created_at, l.updated_at
    FROM raporsmpit.master_lm l
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    LEFT JOIN raporsmpit_vps.master_lm v ON l.id = v.id
    WHERE v.id IS NULL
    ORDER BY l.tingkat, l.mapel_id, l.kode_lm
")->fetchAll();
foreach ($lmLocalOnly as $lm) {
    echo "ID {$lm['id']} | Mapel: {$lm['nama_mapel']} (ID {$lm['mapel_id']}) | Tgt {$lm['tingkat']} | {$lm['semester']} | {$lm['kategori']} | {$lm['kode_lm']} | '{$lm['deskripsi_lm']}'\n";
}

echo "\n=== 3. ANGGOTA ROMBEL: APAKAH ADA ANGGOTA ROMBEL YANG SISWANYA SUDAH HILANG DI VPS? ===\n";
$arOrphanVps = $pdo->query("
    SELECT ar.id, ar.rombel_id, r.nama_rombel, ar.siswa_id
    FROM raporsmpit_vps.anggota_rombel ar
    LEFT JOIN raporsmpit_vps.siswa s ON ar.siswa_id = s.id
    LEFT JOIN raporsmpit_vps.rombel r ON ar.rombel_id = r.id
    WHERE s.id IS NULL
")->fetchAll();
echo "Anggota Rombel VPS yang Siswanya Hilang dari tabel siswa: " . count($arOrphanVps) . "\n";
foreach ($arOrphanVps as $ar) {
    echo "  AR ID {$ar['id']} | Rombel: {$ar['nama_rombel']} (ID {$ar['rombel_id']}) | Siswa ID {$ar['siswa_id']}\n";
}

echo "\n=== 4. ANGGOTA ROMBEL DUPLIKAT DI VPS ===\n";
$arDupVps = $pdo->query("
    SELECT rombel_id, siswa_id, COUNT(*) as cnt
    FROM raporsmpit_vps.anggota_rombel
    GROUP BY rombel_id, siswa_id
    HAVING cnt > 1
")->fetchAll();
echo "Jumlah entri duplikat (rombel_id, siswa_id) di VPS: " . count($arDupVps) . "\n";
foreach ($arDupVps as $dup) {
    $s = $pdo->query("SELECT nama_lengkap, nis FROM raporsmpit_vps.siswa WHERE id = {$dup['siswa_id']}")->fetch();
    $r = $pdo->query("SELECT nama_rombel FROM raporsmpit_vps.rombel WHERE id = {$dup['rombel_id']}")->fetch();
    echo "  Siswa ID {$dup['siswa_id']} ({$s['nama_lengkap']}, NIS {$s['nis']}) muncul {$dup['cnt']} kali di Rombel {$r['nama_rombel']}\n";
}

echo "\n=== 5. ANGGOTA ROMBEL DUPLIKAT DI LOKAL ===\n";
$arDupLoc = $pdo->query("
    SELECT rombel_id, siswa_id, COUNT(*) as cnt
    FROM raporsmpit.anggota_rombel
    GROUP BY rombel_id, siswa_id
    HAVING cnt > 1
")->fetchAll();
echo "Jumlah entri duplikat (rombel_id, siswa_id) di Lokal: " . count($arDupLoc) . "\n";
foreach ($arDupLoc as $dup) {
    $s = $pdo->query("SELECT nama_lengkap, nis FROM raporsmpit.siswa WHERE id = {$dup['siswa_id']}")->fetch();
    $r = $pdo->query("SELECT nama_rombel FROM raporsmpit.rombel WHERE id = {$dup['rombel_id']}")->fetch();
    echo "  Siswa ID {$dup['siswa_id']} ({$s['nama_lengkap']}, NIS {$s['nis']}) muncul {$dup['cnt']} kali di Rombel {$r['nama_rombel']}\n";
}

echo "\n=== 6. ANALISIS NILAI FORMATIF & RAPOR LEBIH BANYAK DI VPS ===\n";
echo "Cek waktu input terakhir pada TA 10 (2026/2027 Ganjil):\n";
$recentFormatifVps = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit_vps.nilai_formatif 
    WHERE tahun_ajaran_id = 10
")->fetch();
$recentFormatifLoc = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit.nilai_formatif 
    WHERE tahun_ajaran_id = 10
")->fetch();
echo "Nilai Formatif TA 10 - VPS Max Created: {$recentFormatifVps['max_c']}, Updated: {$recentFormatifVps['max_u']}\n";
echo "Nilai Formatif TA 10 - Lok Max Created: {$recentFormatifLoc['max_c']}, Updated: {$recentFormatifLoc['max_u']}\n";

$recentRaporVps = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit_vps.nilai_rapor 
    WHERE tahun_ajaran_id = 10
")->fetch();
$recentRaporLoc = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit.nilai_rapor 
    WHERE tahun_ajaran_id = 10
")->fetch();
echo "Nilai Rapor TA 10    - VPS Max Created: {$recentRaporVps['max_c']}, Updated: {$recentRaporVps['max_u']}\n";
echo "Nilai Rapor TA 10    - Lok Max Created: {$recentRaporLoc['max_c']}, Updated: {$recentRaporLoc['max_u']}\n";

$recentSumatifVps = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit_vps.nilai_sumatif 
    WHERE tahun_ajaran_id = 10
")->fetch();
$recentSumatifLoc = $pdo->query("
    SELECT MAX(created_at) as max_c, MAX(updated_at) as max_u 
    FROM raporsmpit.nilai_sumatif 
    WHERE tahun_ajaran_id = 10
")->fetch();
echo "Nilai Sumatif TA 10  - VPS Max Created: {$recentSumatifVps['max_c']}, Updated: {$recentSumatifVps['max_u']}\n";
echo "Nilai Sumatif TA 10  - Lok Max Created: {$recentSumatifLoc['max_c']}, Updated: {$recentSumatifLoc['max_u']}\n";

echo "\n=== 7. NILAI FORMATIF & RAPOR YANG ADA DI VPS TAPI TIDAK DI LOKAL PADA TA 10 ===\n";
// Cek record nilai_formatif di VPS yang tidak ada di lokal
$vpsExtraFormatif = $pdo->query("
    SELECT v.mapel_id, m.nama_mapel, COUNT(*) as cnt
    FROM raporsmpit_vps.nilai_formatif v
    LEFT JOIN raporsmpit_vps.mata_pelajaran m ON v.mapel_id = m.id
    LEFT JOIN raporsmpit.nilai_formatif l ON v.id = l.id
    WHERE v.tahun_ajaran_id = 10 AND l.id IS NULL
    GROUP BY v.mapel_id
")->fetchAll();
echo "Record Formatif VPS yg TIDAK ADA di Lokal (berdasarkan ID):\n";
foreach ($vpsExtraFormatif as $row) {
    echo "  Mapel: {$row['nama_mapel']} (ID {$row['mapel_id']}): {$row['cnt']} baris\n";
}

$locExtraFormatif = $pdo->query("
    SELECT l.mapel_id, m.nama_mapel, COUNT(*) as cnt
    FROM raporsmpit.nilai_formatif l
    LEFT JOIN raporsmpit.mata_pelajaran m ON l.mapel_id = m.id
    LEFT JOIN raporsmpit_vps.nilai_formatif v ON l.id = v.id
    WHERE l.tahun_ajaran_id = 10 AND v.id IS NULL
    GROUP BY l.mapel_id
")->fetchAll();
echo "Record Formatif Lokal yg TIDAK ADA di VPS (berdasarkan ID):\n";
foreach ($locExtraFormatif as $row) {
    echo "  Mapel: {$row['nama_mapel']} (ID {$row['mapel_id']}): {$row['cnt']} baris\n";
}

