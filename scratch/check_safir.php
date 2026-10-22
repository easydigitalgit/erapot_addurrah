<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== ROMBEL SAFIR ===\n";
$res = $mysqli->query("SELECT r.id, r.nama_rombel, r.tingkat, r.id_tahun_ajaran, ta.tahun, ta.semester, ta.status 
                       FROM rombel r 
                       LEFT JOIN tahun_ajaran ta ON r.id_tahun_ajaran = ta.id 
                       WHERE r.nama_rombel LIKE '%Safir%'");
while ($r = $res->fetch_assoc()) {
    print_r($r);
}

echo "\n=== SISWA DI ROMBEL SAFIR TAHUN AJARAN AKTIF ===\n";
$res2 = $mysqli->query("SELECT ar.siswa_id, s.nis, s.nisn, s.nama_lengkap, s.status_siswa, ar.rombel_id 
                        FROM anggota_rombel ar 
                        JOIN siswa s ON ar.siswa_id = s.id 
                        JOIN rombel r ON ar.rombel_id = r.id 
                        JOIN tahun_ajaran ta ON r.id_tahun_ajaran = ta.id 
                        WHERE r.nama_rombel LIKE '%Safir%' AND ta.status = 'Aktif'
                        ORDER BY s.nama_lengkap ASC");
echo "Total siswa di Safir aktif: " . $res2->num_rows . "\n";
while ($r = $res2->fetch_assoc()) {
    echo $r['siswa_id'] . " | " . $r['nis'] . " | " . $r['nama_lengkap'] . "\n";
}
