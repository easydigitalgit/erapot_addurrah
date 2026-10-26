<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
$ids = [4, 99, 124, 136, 146, 150, 162, 176, 182, 212, 223, 427, 432, 506, 508, 518];
$res = $mysqli->query("SELECT id, siswa_id, nama_ayah, nama_ibu FROM orangtua_wali WHERE id IN (" . implode(',', $ids) . ")");
echo "Existing rows with these IDs in orangtua_wali: " . $res->num_rows . "\n";
while ($r = $res->fetch_assoc()) {
    print_r($r);
}
