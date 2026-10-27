<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');
$stmt = $pdo->query("SELECT * FROM rombel WHERE nama_rombel LIKE '%Amazonite%' OR nama_rombel LIKE '%Opal%' OR nama_rombel LIKE '%Zamrud%'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt2 = $pdo->query("SELECT r.id, r.nama_rombel, r.tingkat, r.tahun_ajaran_id, count(f.id) as cnt_f 
    FROM rombel r 
    LEFT JOIN nilai_formatif f ON f.rombel_id = r.id AND f.mapel_id = 10
    WHERE r.nama_rombel LIKE '%Amazonite%' OR r.nama_rombel LIKE '%Opal%' OR r.nama_rombel LIKE '%Zamrud%'
    GROUP BY r.id, r.nama_rombel, r.tingkat, r.tahun_ajaran_id");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));

