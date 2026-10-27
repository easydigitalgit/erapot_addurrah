<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$res = $pdo->query("SELECT * FROM nilai_rapor WHERE siswa_id = 33 AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester'")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

