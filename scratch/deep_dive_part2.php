<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$dupVps = $pdo->query('SELECT COUNT(*) FROM (SELECT rombel_id, siswa_id FROM raporsmpit_vps.anggota_rombel GROUP BY rombel_id, siswa_id HAVING COUNT(*) > 1) t')->fetchColumn();
$dupLoc = $pdo->query('SELECT COUNT(*) FROM (SELECT rombel_id, siswa_id FROM raporsmpit.anggota_rombel GROUP BY rombel_id, siswa_id HAVING COUNT(*) > 1) t')->fetchColumn();

echo "Total duplikat pasang (rombel_id, siswa_id) di VPS: $dupVps\n";
echo "Total duplikat pasang (rombel_id, siswa_id) di Lokal: $dupLoc\n";

// Cek detail anggota_rombel di lokal
$arLocDetail = $pdo->query('SELECT rombel_id, siswa_id, COUNT(*) as cnt FROM raporsmpit.anggota_rombel GROUP BY rombel_id, siswa_id HAVING cnt > 1')->fetchAll();
foreach ($arLocDetail as $d) {
    echo "  Lokal Dup: Rombel {$d['rombel_id']}, Siswa {$d['siswa_id']} (x{$d['cnt']})\n";
}

// Cek total row anggota_rombel
$totArLoc = $pdo->query('SELECT COUNT(*) FROM raporsmpit.anggota_rombel')->fetchColumn();
$totArVps = $pdo->query('SELECT COUNT(*) FROM raporsmpit_vps.anggota_rombel')->fetchColumn();
echo "Total rows anggota_rombel - Lokal: $totArLoc, VPS: $totArVps\n";

// Cek apakah 24 siswa yang hilang di VPS itu ada di orangtua_wali
$missingSiswaIds = [4,9,11,12,24,40,44,72,75,77,114,118,119,120,121,122,123,124,125,440,444,446,534,540];
$idsStr = implode(',', $missingSiswaIds);
$otwVps = $pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.orangtua_wali WHERE siswa_id IN ($idsStr)")->fetchColumn();
$otwLoc = $pdo->query("SELECT COUNT(*) FROM raporsmpit.orangtua_wali WHERE siswa_id IN ($idsStr)")->fetchColumn();
echo "Orangtua wali untuk 24 siswa hilang: VPS = $otwVps, Lokal = $otwLoc\n";

// Cek users untuk 24 siswa hilang
$usersVps = $pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.users u JOIN raporsmpit.siswa s ON u.id = s.user_id WHERE s.id IN ($idsStr)")->fetchColumn();
$usersLoc = $pdo->query("SELECT COUNT(*) FROM raporsmpit.users u JOIN raporsmpit.siswa s ON u.id = s.user_id WHERE s.id IN ($idsStr)")->fetchColumn();
echo "Akun user untuk 24 siswa hilang: VPS = $usersVps, Lokal = $usersLoc\n";

