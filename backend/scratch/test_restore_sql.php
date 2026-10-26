<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
$sql = file_get_contents('docs/restore_orangtua_siswa_hilang.sql');

$mysqli->begin_transaction();
try {
    if ($mysqli->multi_query($sql)) {
        do {
            if ($res = $mysqli->store_result()) {
                $res->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    }
    
    // Check if error occurred
    if ($mysqli->error) {
        throw new Exception($mysqli->error);
    }
    
    // Count inserted
    $c = $mysqli->query("SELECT count(*) FROM orangtua_wali WHERE siswa_id IN (9, 11, 12, 14, 33, 44, 45, 47, 48, 49, 50, 437, 442, 536, 538, 548)")->fetch_row()[0];
    echo "Dry run SUCCESS! Rows matched: $c / 16\n";
    
    // Rollback so we don't commit without user's explicit request
    $mysqli->rollback();
    echo "Dry run ROLLBACK completed successfully. No changes applied yet.\n";
} catch (Exception $e) {
    $mysqli->rollback();
    echo "Error during dry run: " . $e->getMessage() . "\n";
}
