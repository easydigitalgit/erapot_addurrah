<?php
$sqlBackup = 'backend/writable/backups/Auto_Full_20261012_130712.sql';
$handle = fopen($sqlBackup, 'r');
$in_ow = false;
$missing_ids = [9, 11, 12, 14, 33, 44, 45, 47, 48, 49, 50, 437, 442, 536, 538, 548];
$statements = [];

while (($line = fgets($handle)) !== false) {
    if (strpos($line, "INSERT INTO `orangtua_wali`") !== false) {
        $in_ow = true;
    }
    if ($in_ow) {
        foreach ($missing_ids as $id) {
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

echo "-- RESTORE SQL UNTUK ORANGTUA WALI 16 SISWA TERMASUK FIRZANA:\n";
foreach ($statements as $id => $sql) {
    echo $sql . "\n";
}
