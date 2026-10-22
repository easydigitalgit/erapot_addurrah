<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== SCAN ALL TABLES FOR SISWA ID 581-620 ===\n";

$tables = $mysqli->query("SHOW TABLES");
$allFound = [];

while ($tRow = $tables->fetch_array()) {
    $table = $tRow[0];
    if ($table === 'siswa') continue;

    // Check all columns in this table
    $cols = $mysqli->query("SHOW COLUMNS FROM `$table`");
    $candCols = [];
    while ($c = $cols->fetch_assoc()) {
        $fieldName = $c['Field'];
        if (preg_match('/siswa|user|id/i', $fieldName)) {
            $candCols[] = $fieldName;
        }
    }

    foreach ($candCols as $col) {
        $q = "SELECT COUNT(*) as cnt FROM `$table` WHERE `$col` BETWEEN 581 AND 620";
        try {
            $cnt = $mysqli->query($q)->fetch_assoc()['cnt'];
            if ($cnt > 0) {
                echo "Found $cnt records in table `$table` column `$col`\n";
                $allFound[$table . '.' . $col] = $cnt;
            }
        } catch (\Exception $e) {
            // ignore type mismatch
        }
    }
}

echo "\nSummary of references to 581-620:\n";
print_r($allFound);
