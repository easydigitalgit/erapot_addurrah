<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

foreach ([10 => 'B. Arab', 44 => 'Nahwu', 11 => 'Hadist'] as $mid => $mname) {
    echo "=== Mapel $mname (ID $mid) di Topaz (Rombel 73) ===\n";
    $f = $pdo->query("SELECT COUNT(*) FROM nilai_formatif WHERE rombel_id = 73 AND mapel_id = $mid AND tahun_ajaran_id = 10")->fetchColumn();
    $s = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = 73 AND ns.mapel_id = $mid AND ns.tahun_ajaran_id = 10")->fetchColumn();
    $r = $pdo->query("SELECT COUNT(*) FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = 73 AND nr.mapel_id = $mid AND nr.tahun_ajaran_id = 10")->fetchColumn();
    echo "Formatif = $f, Sumatif = $s, Rapor = $r\n";
}

