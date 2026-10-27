<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== CEK NILAI B. ARAB & NAHWU OLEH GURU 34 DI SELURUH KELAS VII ===\n";

$rombelsVII = $pdo->query("SELECT id, nama_rombel FROM rombel WHERE tingkat = 'VII'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rombelsVII as $r) {
    echo "\n>>> ROMBEL VII {$r['nama_rombel']} (ID {$r['id']}):\n";
    foreach ([10 => 'Bahasa Arab', 44 => 'Nahwu'] as $mid => $mname) {
        $fCount = $pdo->query("SELECT COUNT(*) FROM nilai_formatif WHERE rombel_id = {$r['id']} AND mapel_id = $mid AND tahun_ajaran_id = 10")->fetchColumn();
        $sCount = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = {$r['id']} AND ns.mapel_id = $mid AND ns.tahun_ajaran_id = 10")->fetchColumn();
        $rCount = $pdo->query("SELECT COUNT(*) FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = {$r['id']} AND nr.mapel_id = $mid AND nr.tahun_ajaran_id = 10")->fetchColumn();
        echo "  - $mname (ID $mid): Formatif = $fCount | Sumatif = $sCount | Rapor = $rCount\n";
    }
}

