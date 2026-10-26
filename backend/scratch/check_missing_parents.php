<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
$missing_res = $mysqli->query("
    SELECT s.id, s.nis, s.nisn, s.nama_lengkap, s.rombel_id, r.nama_rombel 
    FROM siswa s 
    LEFT JOIN orangtua_wali ow ON s.id = ow.siswa_id 
    LEFT JOIN rombel r ON s.rombel_id = r.id
    WHERE ow.id IS NULL
");

$missing_ids = [];
while ($row = $missing_res->fetch_assoc()) {
    $missing_ids[$row['id']] = $row;
}

echo "Missing siswa IDs: " . implode(', ', array_keys($missing_ids)) . "\n\n";

$sqlBackup = 'backend/writable/backups/Auto_Full_20261012_130712.sql';
$handle = fopen($sqlBackup, 'r');
$in_ow = false;
$found_ow = [];

while (($line = fgets($handle)) !== false) {
    if (strpos($line, "INSERT INTO `orangtua_wali`") !== false) {
        $in_ow = true;
    }
    if ($in_ow) {
        foreach ($missing_ids as $id => $info) {
            // orangtua_wali row format: VALUES('id', 'user_id', 'siswa_id', ...)
            // check for ,'id', or ,'$id',
            if (preg_match("/VALUES\s*\(\s*'(\d+)'\s*,\s*('[^']*'|NULL)\s*,\s*'{$id}'\s*,/", $line, $m)) {
                $found_ow[$id] = trim($line);
            }
        }
    }
    if ($in_ow && strpos($line, ";") !== false && strpos($line, "INSERT INTO `orangtua_wali`") === false) {
        $in_ow = false;
    }
}
fclose($handle);

echo "Found in backup for missing siswa:\n";
foreach ($missing_ids as $id => $info) {
    echo "Siswa ID $id ({$info['nama_lengkap']}): " . (isset($found_ow[$id]) ? "ADA DI BACKUP" : "TIDAK ADA DI BACKUP") . "\n";
    if (isset($found_ow[$id])) {
        echo "  " . substr($found_ow[$id], 0, 150) . "...\n";
    }
}
