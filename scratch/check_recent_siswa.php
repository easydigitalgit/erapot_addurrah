<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== SISWA DENGAN ID > 400 ===\n";
$res = $mysqli->query("SELECT id, nis, nama_lengkap, rombel_id FROM siswa WHERE id > 400 ORDER BY id DESC");
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | NIS: {$r['nis']} | Nama: {$r['nama_lengkap']} | Rombel: {$r['rombel_id']}\n";
}
