<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');
if ($m->multi_query($sql)) {
    do {
        if ($res = $m->store_result()) $res->free();
    } while ($m->more_results() && $m->next_result());
    echo "ALL QUERIES EXECUTED PERFECTLY WITH 0 ERRORS!\n";
} else {
    echo "ERROR: " . $m->error . "\n";
}
