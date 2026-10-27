<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== JUMLAH RECORD DI NILAI_RAPOR (TA 10, Tengah Semester) PER MAPEL DI KELAS VIII TOPAZ ===" . PHP_EOL;

$topaz_siswa = $pdo->query("
    SELECT s.id, s.nama_lengkap 
    FROM siswa s 
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
    JOIN rombel r ON r.id = ar.rombel_id 
    WHERE r.nama_rombel = 'Topaz'
")->fetchAll(PDO::FETCH_ASSOC);

echo "Total siswa di VIII Topaz: " . count($topaz_siswa) . PHP_EOL;

$mapels = $pdo->query("
    SELECT m.id, m.nama_mapel,
           (SELECT COUNT(*) FROM nilai_rapor nr 
            JOIN siswa s ON s.id = nr.siswa_id 
            JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
            JOIN rombel r ON r.id = ar.rombel_id 
            WHERE r.nama_rombel = 'Topaz' AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = 10 AND nr.kategori = 'Tengah Semester') as count_rapor,
           (SELECT COUNT(DISTINCT nf.siswa_id) FROM nilai_formatif nf 
            JOIN siswa s ON s.id = nf.siswa_id 
            JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
            JOIN rombel r ON r.id = ar.rombel_id 
            WHERE r.nama_rombel = 'Topaz' AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = 10 AND nf.kategori = 'Tengah Semester') as count_formatif_siswa,
           (SELECT COUNT(DISTINCT ns.siswa_id) FROM nilai_sumatif ns 
            JOIN siswa s ON s.id = ns.siswa_id 
            JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
            JOIN rombel r ON r.id = ar.rombel_id 
            WHERE r.nama_rombel = 'Topaz' AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = 10 AND ns.kategori = 'Tengah Semester') as count_sumatif_siswa
    FROM mata_pelajaran m
    ORDER BY m.id
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($mapels as $m) {
    if ($m['count_formatif_siswa'] > 0 || $m['count_sumatif_siswa'] > 0 || $m['count_rapor'] > 0) {
        echo sprintf(
            "- Mapel [%2d] %-35s : Formatif = %2d siswa | Sumatif = %2d siswa | Rapor = %2d siswa\n",
            $m['id'], $m['nama_mapel'], $m['count_formatif_siswa'], $m['count_sumatif_siswa'], $m['count_rapor']
        );
    }
}
