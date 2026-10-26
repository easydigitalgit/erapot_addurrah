<?php
$sqlBackup = 'backend/writable/backups/Auto_Full_20261012_130712.sql';
$handle = fopen($sqlBackup, 'r');
$in_ow = false;
$all_16_ids = [9, 11, 12, 14, 33, 44, 45, 47, 48, 49, 50, 437, 442, 536, 538, 548];
$statements = [];

while (($line = fgets($handle)) !== false) {
    if (strpos($line, "INSERT INTO `orangtua_wali`") !== false) {
        $in_ow = true;
    }
    if ($in_ow) {
        foreach ($all_16_ids as $id) {
            if (preg_match("/VALUES\s*\(\s*'(\d+)'\s*,\s*('[^']*'|NULL)\s*,\s*'{$id}'\s*,/", $line, $m)) {
                $statements[$id] = trim($line);
            }
        }
    }
    if ($in_ow && strpos($line, ";") !== false && strpos($line, "INSERT INTO `orangtua_wali`") === false) {
        $in_ow = false;
    }
}
fclose($handle);

$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
foreach ($all_16_ids as $id) {
    $s = $mysqli->query("SELECT s.id, s.nama_lengkap, s.nis, r.nama_rombel FROM siswa s LEFT JOIN rombel r ON s.rombel_id = r.id WHERE s.id = $id")->fetch_assoc();
    echo "-- Siswa ID: {$s['id']} | Nama: {$s['nama_lengkap']} | Kelas: {$s['nama_rombel']}\n";
    echo $statements[$id] . "\n\n";
}
