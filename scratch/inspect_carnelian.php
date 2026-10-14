<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== ROMBEL DETAIL (55 & 80) ===\n";
$rombel = $pdo->query("SELECT * FROM rombel WHERE id IN (55, 80)")->fetchAll(PDO::FETCH_ASSOC);
print_r($rombel);

echo "=== SISWA DI ROMBEL 55 ===\n";
$s55 = $pdo->query("SELECT id, nama_lengkap, nis, nisn, rombel_id FROM siswa WHERE rombel_id = 55 ORDER BY nama_lengkap")->fetchAll(PDO::FETCH_ASSOC);
foreach ($s55 as $s) {
    echo "ID: {$s['id']} | NIS: {$s['nis']} | NISN: {$s['nisn']} | Nama: {$s['nama_lengkap']}\n";
}

echo "\n=== ANGGOTA ROMBEL UNTUK SISWA DI ROMBEL 80 ===\n";
$ar80 = $pdo->query("SELECT min(created_at) as min_c, max(created_at) as max_c, count(*) as c FROM anggota_rombel WHERE rombel_id = 80")->fetch(PDO::FETCH_ASSOC);
print_r($ar80);

echo "\n=== DETAIL LOGIN TIKA YEARDILA ===\n";
$wali = $pdo->query("SELECT u.id as user_id, u.username, u.role_id, gt.* FROM guru_tendik gt JOIN users u ON u.id = gt.user_id WHERE gt.id = 44")->fetch(PDO::FETCH_ASSOC);
print_r($wali);


echo "\n=== ANGGOTA ROMBEL UNTUK SISWA DI ROMBEL 55 ===\n";
foreach ($s55 as $s) {
    $ar = $pdo->query("SELECT * FROM anggota_rombel WHERE siswa_id = {$s['id']}")->fetchAll(PDO::FETCH_ASSOC);
    echo "Siswa {$s['nama_lengkap']} (ID: {$s['id']}): " . json_encode($ar) . "\n";
}

echo "\n=== CEK SEMUA ROMBEL DI DATABASE (NAMA, TINGKAT, TA) ===\n";
$allRombel = $pdo->query("SELECT id, nama_rombel, tingkat, id_tahun_ajaran, wali_kelas_id FROM rombel ORDER BY nama_rombel, tingkat")->fetchAll(PDO::FETCH_ASSOC);
foreach ($allRombel as $ar) {
    echo "ID: {$ar['id']} | {$ar['nama_rombel']} | Tingkat: {$ar['tingkat']} | TA: " . ($ar['id_tahun_ajaran'] ?? 'NULL') . " | Wali: {$ar['wali_kelas_id']}\n";
}
