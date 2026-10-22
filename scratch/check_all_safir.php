<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CHECK ROMBEL SAFIR ===\n";
$resR = $mysqli->query("SELECT r.* FROM rombel r WHERE r.nama_rombel LIKE '%safir%'");
while ($r = $resR->fetch_assoc()) {
    echo "Rombel ID: " . $r['id'] . " | " . $r['nama_rombel'] . " | Tingkat: " . $r['tingkat'] . " | TA ID: " . $r['id_tahun_ajaran'] . "\n";
    $resCount = $mysqli->query("SELECT COUNT(*) as total FROM anggota_rombel WHERE rombel_id = " . $r['id'])->fetch_assoc();
    echo "  Total anggota_rombel: " . $resCount['total'] . "\n";
    
    // Check how many have existing siswa record
    $resExist = $mysqli->query("SELECT COUNT(*) as total FROM anggota_rombel ar JOIN siswa s ON ar.siswa_id = s.id WHERE ar.rombel_id = " . $r['id'])->fetch_assoc();
    echo "  Total dengan siswa aktif di db: " . $resExist['total'] . "\n";
}
