<?php
$f = fopen('raporsmpit.sql', 'r');
$orphanIds = [540, 550, 551, 451, 453, 461, 478, 480, 439, 440, 454, 629, 630, 6, 29, 36, 39, 40, 42, 46, 51, 52, 54, 68, 74, 91, 94, 99, 164, 166, 176, 187, 137, 208, 209, 211, 222, 233, 243, 246, 256, 267];

echo "Searching raporsmpit.sql for " . count($orphanIds) . " orphan IDs...\n";
$found = [];

while (($line = fgets($f)) !== false) {
    if (strpos($line, 'INSERT INTO `siswa`') !== false || strpos($line, 'INSERT INTO siswa') !== false) {
        foreach ($orphanIds as $oid) {
            if (preg_match("/\('$oid',|\($oid,/", $line)) {
                $found[$oid] = substr($line, 0, 200) . '...';
            }
        }
    }
}
fclose($f);

echo "Found " . count($found) . " orphan IDs in raporsmpit.sql:\n";
foreach ($found as $id => $snippet) {
    echo "ID $id: $snippet\n";
}
