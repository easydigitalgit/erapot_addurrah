<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$ids = [88, 112, 113, 119, 121, 128, 130];
echo "=== CEK NILAI FORMATIF UNTUK ID HILANG DI SAFIR ===\n";
foreach ($ids as $id) {
    $res = $mysqli->query("SELECT COUNT(*) as cnt, MIN(created_at) as first_created FROM nilai_formatif WHERE siswa_id = $id");
    $r = $res->fetch_assoc();
    echo "Siswa ID $id: " . $r['cnt'] . " nilai formatif\n";
}

echo "\n=== CARI NAMA SISWA INI DARI BACKUP / TABEL LAIN ===\n";
$tables = ['orangtua_wali', 'nilai_ekskul', 'absensi_siswa', 'catatan_wali_kelas', 'prestasi_siswa', 'pelanggaran_siswa'];
foreach ($tables as $t) {
    foreach ($ids as $id) {
        $res = $mysqli->query("SELECT * FROM $t WHERE siswa_id = $id LIMIT 1");
        if ($res && $res->num_rows > 0) {
            $r = $res->fetch_assoc();
            echo "Found in $t for ID $id: " . json_encode($r) . "\n";
        }
    }
}
