<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Cek rombel kelas 8
$rombel8 = $pdo->query("SELECT id, nama_rombel, tingkat FROM raporsmpit.rombel WHERE id_tahun_ajaran = 10 AND tingkat = 'VIII' ORDER BY nama_rombel")->fetchAll();

echo "Coverage Nilai Kelas 8 (TA 2026/2027 Ganjil) saat ini di Lokal:\n";
printf("%-12s | %-25s | %-12s | %-12s\n", "Kelas", "Mapel", "Jml Formatif", "Jml Sumatif");
echo str_repeat("-", 70) . "\n";

foreach ($rombel8 as $r) {
    $mapels = $pdo->query("
        SELECT m.id, m.nama_mapel,
               (SELECT COUNT(*) FROM raporsmpit.nilai_formatif nf WHERE nf.rombel_id = {$r['id']} AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = 10) as cnt_f,
               (SELECT COUNT(*) FROM raporsmpit.nilai_sumatif ns JOIN raporsmpit.siswa s ON ns.siswa_id = s.id WHERE s.rombel_id = {$r['id']} AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = 10) as cnt_s
        FROM raporsmpit.mata_pelajaran m
        HAVING cnt_f > 0 OR cnt_s > 0
        ORDER BY m.nama_mapel
    ")->fetchAll();
    
    foreach ($mapels as $m) {
        printf("%-12s | %-25s | %-12d | %-12d\n", $r['nama_rombel'], $m['nama_mapel'], $m['cnt_f'], $m['cnt_s']);
    }
}
