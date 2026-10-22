<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$res = $m->query("SELECT id, nis, nama_lengkap, status_siswa, rombel_id FROM siswa WHERE id IN (47, 55, 162, 200, 223)");
while ($r = $res->fetch_assoc()) {
    echo $r['id'] . ' | ' . $r['nama_lengkap'] . ' | NIS: ' . $r['nis'] . ' | Status: ' . $r['status_siswa'] . ' | Rombel: ' . $r['rombel_id'] . PHP_EOL;
}
