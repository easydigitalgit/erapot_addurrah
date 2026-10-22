<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK SEMUA MAPEL DAN SISWA DI ROMBEL 76 (SAFIR) ===\n";
$res = $mysqli->query("SELECT ar.siswa_id, s.nis, s.nama_lengkap,
                       (SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id = ar.siswa_id AND rombel_id = 76) as cnt_nf,
                       (SELECT COUNT(*) FROM nilai_sumatif WHERE siswa_id = ar.siswa_id AND tahun_ajaran_id = 10) as cnt_ns,
                       (SELECT COUNT(*) FROM nilai_rapor WHERE siswa_id = ar.siswa_id AND rombel_id = 76) as cnt_nr
                       FROM anggota_rombel ar 
                       LEFT JOIN siswa s ON ar.siswa_id = s.id 
                       WHERE ar.rombel_id = 76");

$withData = 0;
$noData = 0;
while ($r = $res->fetch_assoc()) {
    $has = ($r['cnt_nf'] > 0 || $r['cnt_ns'] > 0 || $r['cnt_nr'] > 0);
    if ($has) $withData++; else $noData++;
    echo "ID: " . $r['siswa_id'] . " | " . ($r['nama_lengkap'] ?? 'HILANG DARI TABEL SISWA') . " | NF: " . $r['cnt_nf'] . " | NS: " . $r['cnt_ns'] . " | NR: " . $r['cnt_nr'] . "\n";
}
echo "Total with data: $withData, Total without data: $noData\n";
