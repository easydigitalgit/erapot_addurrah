<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== ROMBEL IN DB (TA 10) ===\n";
$res = $mysqli->query("SELECT r.id, r.nama_rombel, r.tingkat, r.wali_kelas_id, g.nama_lengkap as wali_kelas
                       FROM rombel r
                       LEFT JOIN guru_tendik g ON r.wali_kelas_id = g.id
                       WHERE r.id_tahun_ajaran = 10
                       ORDER BY r.tingkat, r.nama_rombel");
while ($r = $res->fetch_assoc()) {
    $rid = $r['id'];
    $cntAr = $mysqli->query("SELECT COUNT(*) as c FROM anggota_rombel WHERE rombel_id = $rid")->fetch_assoc()['c'];
    $cntSiswa = $mysqli->query("SELECT COUNT(*) as c FROM anggota_rombel ar JOIN siswa s ON ar.siswa_id = s.id WHERE ar.rombel_id = $rid")->fetch_assoc()['c'];
    echo "Rombel ID: {$r['id']} | {$r['tingkat']} {$r['nama_rombel']} | Wali: {$r['wali_kelas']} | Anggota Rombel: $cntAr | Siswa Aktif: $cntSiswa\n";
}
