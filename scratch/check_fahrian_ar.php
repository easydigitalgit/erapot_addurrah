<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$ar = $pdo->query("SELECT * FROM anggota_rombel WHERE siswa_id = 12")->fetchAll(PDO::FETCH_ASSOC);
print_r($ar);

