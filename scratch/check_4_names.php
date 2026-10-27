<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$names = ['ARSY', 'AFKAR', 'HAFIZ FADLI', 'RADITYA'];
foreach ($names as $n) {
    $rows = $pdo->query("SELECT s.id, s.nama_lengkap FROM siswa s JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = 73 AND s.nama_lengkap LIKE '%$n%'")->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
}

