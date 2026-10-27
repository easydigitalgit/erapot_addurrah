<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$res = $pdo->query("SELECT ar.*, r.nama_rombel, r.tingkat 
                    FROM anggota_rombel ar 
                    JOIN rombel r ON r.id = ar.rombel_id 
                    WHERE ar.siswa_id = 76")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

