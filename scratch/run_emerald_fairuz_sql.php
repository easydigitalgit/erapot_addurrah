<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');

function runSqlFile($mysqli, $file) {
    echo "Running $file...\n";
    $sql = file_get_contents($file);
    if ($mysqli->multi_query($sql)) {
        do {
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
        echo "Successfully executed $file!\n";
    } else {
        echo "Error executing $file: " . $mysqli->error . "\n";
    }
}

runSqlFile($mysqli, 'docs/perbaikan_siswa_kelas_emerald_dan_kolom_tergeser.sql');
runSqlFile($mysqli, 'docs/perbaikan_siswa_kelas_fairuz.sql');
