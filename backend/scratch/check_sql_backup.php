<?php
// Check if raporsmpit.sql exists and search for orangtua_wali or 442
$sql_file = 'd:/laragon/htdocs/erapoteasy/raporsmpit.sql';
if (file_exists($sql_file)) {
    echo "Found raporsmpit.sql (" . filesize($sql_file) . " bytes)\n";
    $handle = fopen($sql_file, 'r');
    $in_orangtua = false;
    $found = [];
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `orangtua_wali`") !== false) {
            $in_orangtua = true;
        }
        if ($in_orangtua) {
            if (strpos($line, "442") !== false || stripos($line, "FIRZANA") !== false) {
                $found[] = $line;
            }
            if (strpos($line, ";") !== false && strpos($line, "INSERT INTO `orangtua_wali`") === false) {
                $in_orangtua = false;
            }
        }
    }
    fclose($handle);
    echo "Found in orangtua_wali: " . count($found) . "\n";
    foreach ($found as $f) {
        echo substr($f, 0, 300) . "\n";
    }
}
