<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$res = $mysqli->query("SELECT id, nis, nisn, nama_lengkap FROM siswa WHERE id >= 581 ORDER BY id ASC");
echo "Total records id >= 581: " . $res->num_rows . "\n";
while ($r = $res->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | NIS: " . $r['nis'] . " | NISN: " . $r['nisn'] . " | Nama: " . $r['nama_lengkap'] . "\n";
}
