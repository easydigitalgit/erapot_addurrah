<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');

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
                $statements[$id] = [
                    'id' => $m[1],
                    'user_id' => $m[2] === 'NULL' ? null : trim($m[2], "'"),
                    'siswa_id' => $id,
                    'raw' => trim($line)
                ];
            }
        }
    }
    if ($in_ow && strpos($line, ";") !== false && strpos($line, "INSERT INTO `orangtua_wali`") === false) {
        $in_ow = false;
    }
}
fclose($handle);

echo "Total found statements: " . count($statements) . "\n\n";

// Check user_ids
foreach ($statements as $id => $data) {
    $uid = $data['user_id'];
    if ($uid !== null) {
        $u = $mysqli->query("SELECT id, username, role_id FROM users WHERE id = $uid")->fetch_assoc();
        if ($u) {
            echo "Siswa $id -> Ortu User ID $uid EXISTS ({$u['username']}, role: {$u['role_id']})\n";
        } else {
            echo "Siswa $id -> Ortu User ID $uid NOT FOUND in users!\n";
        }
    } else {
        echo "Siswa $id -> Ortu User ID is NULL\n";
    }
}

// Also check if any siswa_id exists in siswa
foreach ($all_16_ids as $id) {
    $s = $mysqli->query("SELECT id, nis, nama_lengkap, rombel_id FROM siswa WHERE id = $id")->fetch_assoc();
    if ($s) {
        echo "Siswa $id ({$s['nama_lengkap']}) EXISTS in siswa (NIS: {$s['nis']}, Rombel: {$s['rombel_id']})\n";
    } else {
        echo "Siswa $id NOT FOUND in siswa!\n";
    }
}
