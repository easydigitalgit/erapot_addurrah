<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK SEMUA ROMBEL DI TA 10 ===\n";
$resR = $mysqli->query("SELECT r.id, r.nama_rombel, r.tingkat FROM rombel r WHERE r.id_tahun_ajaran = 10 ORDER BY r.nama_rombel");
while ($r = $resR->fetch_assoc()) {
    $rid = $r['id'];
    $totAr = $mysqli->query("SELECT COUNT(*) as c FROM anggota_rombel WHERE rombel_id = $rid")->fetch_assoc()['c'];
    $totSiswaExist = $mysqli->query("SELECT COUNT(*) as c FROM anggota_rombel ar JOIN siswa s ON ar.siswa_id = s.id WHERE ar.rombel_id = $rid")->fetch_assoc()['c'];
    $diff = $totAr - $totSiswaExist;
    
    // Check missing grades in any mapel
    echo "Rombel {$r['nama_rombel']} (ID $rid): Total Anggota = $totAr, Siswa Ada = $totSiswaExist, Siswa Hilang di tabel siswa = $diff\n";
}
