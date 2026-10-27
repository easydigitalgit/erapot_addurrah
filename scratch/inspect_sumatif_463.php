<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$res = $pdo->query("SELECT * FROM nilai_sumatif WHERE siswa_id IN (463, 436) AND tahun_ajaran_id = 10 ORDER BY siswa_id, mapel_id, id")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

