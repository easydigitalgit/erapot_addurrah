<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$ta_id = 10;
$kategori = 'Tengah Semester';

echo "=== AUDIT KELENGKAPAN NILAI RAPOR KELAS 7 & KELAS 9 ===" . PHP_EOL;

foreach (['VII', 'IX'] as $tingkat) {
    echo "\n>>> TINGKAT $tingkat:\n";
    $rombels = $pdo->query("SELECT id, nama_rombel FROM rombel WHERE tingkat = '$tingkat' ORDER BY nama_rombel")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rombels as $r) {
        $siswas = $pdo->query("SELECT s.id, s.nama_lengkap FROM siswa s JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = $ta_id WHERE ar.rombel_id = {$r['id']}")->fetchAll(PDO::FETCH_ASSOC);
        if (empty($siswas)) continue;

        // Mapel aktif di rombel
        $stmtMapel = $pdo->prepare("
            SELECT DISTINCT m.id, m.nama_mapel
            FROM mata_pelajaran m
            WHERE m.status = 'Aktif'
              AND LOWER(m.nama_mapel) NOT IN ('tahfidz', 'tahfiz', 'bpi')
              AND (
                  EXISTS (SELECT 1 FROM nilai_formatif nf JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = ?)
                  OR
                  EXISTS (SELECT 1 FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = ?)
                  OR
                  EXISTS (SELECT 1 FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = ?)
              )
        ");
        $stmtMapel->execute([$ta_id, $r['id'], $ta_id, $ta_id, $r['id'], $ta_id, $ta_id, $r['id'], $ta_id]);
        $mapels = $stmtMapel->fetchAll(PDO::FETCH_ASSOC);

        $missing_count = 0;
        foreach ($siswas as $s) {
            foreach ($mapels as $m) {
                $nr = $pdo->query("SELECT nilai_akhir FROM nilai_rapor WHERE siswa_id = {$s['id']} AND mapel_id = {$m['id']} AND tahun_ajaran_id = $ta_id AND kategori = '$kategori'")->fetchColumn();
                if ($nr === false || $nr === null || $nr === '') {
                    $missing_count++;
                }
            }
        }

        echo "  - Rombel {$r['nama_rombel']}: " . count($siswas) . " siswa, " . count($mapels) . " mapel -> Missing rapor: $missing_count\n";
    }
}

