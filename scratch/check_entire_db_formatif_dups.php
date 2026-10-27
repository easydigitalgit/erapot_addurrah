<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$sql = "
    SELECT siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, '') as kat, jenis_penilaian, pertemuan, COUNT(*) as c
    FROM nilai_formatif
    GROUP BY siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, ''), jenis_penilaian, pertemuan
    HAVING c > 1
";
$allDupsF = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
echo "Total grup duplikat nilai_formatif di SELURUH DATABASE: " . count($allDupsF) . PHP_EOL;

