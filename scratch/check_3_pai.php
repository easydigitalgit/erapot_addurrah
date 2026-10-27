<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
foreach ([39, 423, 429] as $sid) {
    $f = $pdo->query("SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id = $sid AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();
    $s = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif WHERE siswa_id = $sid AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();
    $r = $pdo->query("SELECT COUNT(*) FROM nilai_rapor WHERE siswa_id = $sid AND mapel_id = 1 AND tahun_ajaran_id = 10")->fetchColumn();
    echo "Siswa ID $sid: Formatif = $f, Sumatif = $s, Rapor = $r\n";
}

