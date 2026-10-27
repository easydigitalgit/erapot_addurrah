<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== STATUS SINKRONISASI NILAI_RAPOR SELURUH ROMBEL DI KELAS VIII (TA 10, Tengah Semester) ===" . PHP_EOL;

$rombels = $pdo->query("SELECT id, nama_rombel FROM rombel WHERE tingkat = 'VIII'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rombels as $r) {
    echo "\n>>> ROMBEL {$r['nama_rombel']} (ID {$r['id']}):\n";
    $sql = "
        SELECT m.id, m.nama_mapel,
               (SELECT COUNT(DISTINCT nf.siswa_id) FROM nilai_formatif nf JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = {$r['id']} AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = 10 AND nf.kategori = 'Tengah Semester') as f_count,
               (SELECT COUNT(DISTINCT ns.siswa_id) FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = {$r['id']} AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = 10 AND ns.kategori = 'Tengah Semester') as s_count,
               (SELECT COUNT(*) FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = {$r['id']} AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = 10 AND nr.kategori = 'Tengah Semester') as r_count
        FROM mata_pelajaran m
        HAVING f_count > 0 OR s_count > 0 OR r_count > 0
        ORDER BY m.id
    ";
    $stats = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($stats as $st) {
        $status = ($st['r_count'] == 0 && ($st['f_count'] > 0 || $st['s_count'] > 0)) ? ">>> PERLU SINKRONISASI RAPOR <<<" : "OK";
        echo sprintf("  - Mapel [%2d] %-30s | Formatif: %2d | Sumatif: %2d | Rapor: %2d [%s]\n", $st['id'], $st['nama_mapel'], $st['f_count'], $st['s_count'], $st['r_count'], $status);
    }
}
