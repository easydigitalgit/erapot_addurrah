<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$ids = [88, 112, 113, 119, 121, 128, 130];
echo "=== CEK orangtua_wali UNTUK 7 SISWA SAFIR ===\n";
foreach ($ids as $id) {
    $ot = $m->query("SELECT * FROM orangtua_wali WHERE siswa_id = $id")->fetch_assoc();
    if ($ot) {
        echo "ID $id: Ayah={$ot['nama_ayah']}, Ibu={$ot['nama_ibu']}, Wali={$ot['nama_wali']}\n";
    } else {
        echo "ID $id: tidak ada di orangtua_wali\n";
    }
}
