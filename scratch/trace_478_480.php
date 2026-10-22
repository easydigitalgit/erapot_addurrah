<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== TRACE 478 and 480 ===\n";
$res = $mysqli->query("SELECT DISTINCT mapel_id, rombel_id FROM nilai_formatif WHERE siswa_id IN (478, 480)");
while ($r = $res->fetch_assoc()) {
    echo "Siswa in formatif mapel {$r['mapel_id']}, rombel {$r['rombel_id']}\n";
}

// Check other tables
$tables = ['absensi_siswa', 'catatan_wali_kelas', 'nilai_ekskul', 'orangtua_wali'];
foreach ($tables as $t) {
    $resT = $mysqli->query("SHOW TABLES LIKE '$t'");
    if ($resT->num_rows > 0) {
        $c = $mysqli->query("SELECT COUNT(*) as cnt FROM $t WHERE siswa_id IN (478, 480)")->fetch_assoc()['cnt'] ?? 0;
        echo "$t: $c records\n";
    }
}
