<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK 22 SISWA DI ANGGOTA ROMBEL 76 (VIII SAFIR) ===\n";
$res = $mysqli->query("SELECT ar.id as ar_id, ar.siswa_id, s.nis, s.nama_lengkap, s.status_siswa, s.rombel_id as siswa_rombel_id, r.nama_rombel as current_rombel_nama
                       FROM anggota_rombel ar 
                       LEFT JOIN siswa s ON ar.siswa_id = s.id 
                       LEFT JOIN rombel r ON s.rombel_id = r.id
                       WHERE ar.rombel_id = 76 
                       ORDER BY s.nama_lengkap ASC");
$no = 1;
while ($r = $res->fetch_assoc()) {
    echo $no++ . ". ID: " . $r['siswa_id'] . " | NIS: " . $r['nis'] . " | " . $r['nama_lengkap'] . " | siswa.rombel_id: " . $r['siswa_rombel_id'] . " (" . ($r['current_rombel_nama'] ?? 'NULL') . ") | status: " . $r['status_siswa'] . "\n";
}
