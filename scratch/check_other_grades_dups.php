<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$sqlRapor = "
    SELECT siswa_id, rombel_id, tahun_ajaran_id, mapel_id, IFNULL(kategori, '') as kat, COUNT(*) as c
    FROM nilai_rapor
    GROUP BY siswa_id, rombel_id, tahun_ajaran_id, mapel_id, IFNULL(kategori, '')
    HAVING c > 1
";
$dupsR = $pdo->query($sqlRapor)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikat nilai_rapor: " . count($dupsR) . PHP_EOL;

$sqlKomp = "
    SELECT siswa_id, rombel_id, tahun_ajaran_id, mapel_id, COUNT(*) as c
    FROM nilai_komponen
    GROUP BY siswa_id, rombel_id, tahun_ajaran_id, mapel_id
    HAVING c > 1
";
$dupsK = $pdo->query($sqlKomp)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikat nilai_komponen: " . count($dupsK) . PHP_EOL;

