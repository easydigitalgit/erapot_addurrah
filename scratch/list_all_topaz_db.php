<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$rows = $pdo->query("SELECT s.id, s.nama_lengkap FROM siswa s JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = 73 ORDER BY s.nama_lengkap")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "ID {$r['id']}: {$r['nama_lengkap']}\n";
}

