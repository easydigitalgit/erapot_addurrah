<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== INSPECT EXTRA IN OPAL (78) ===\n";
$resOpal = $mysqli->query("SELECT ar.id, ar.siswa_id, s.nama_lengkap, s.nis,
                          (SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id=ar.siswa_id) as cnt_nf
                          FROM anggota_rombel ar 
                          LEFT JOIN siswa s ON ar.siswa_id = s.id 
                          WHERE ar.rombel_id = 78");
while ($r = $resOpal->fetch_assoc()) {
    echo "ar.id: {$r['id']} | siswa_id: {$r['siswa_id']} | " . ($r['nama_lengkap'] ?? 'ORPHAN') . " | NF: {$r['cnt_nf']}\n";
}

echo "\n=== INSPECT EXTRA IN TOPAZ (73) ===\n";
$resTopaz = $mysqli->query("SELECT ar.id, ar.siswa_id, s.nama_lengkap, s.nis,
                            (SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id=ar.siswa_id) as cnt_nf
                            FROM anggota_rombel ar 
                            LEFT JOIN siswa s ON ar.siswa_id = s.id 
                            WHERE ar.rombel_id = 73");
while ($r = $resTopaz->fetch_assoc()) {
    echo "ar.id: {$r['id']} | siswa_id: {$r['siswa_id']} | " . ($r['nama_lengkap'] ?? 'ORPHAN') . " | NF: {$r['cnt_nf']}\n";
}
