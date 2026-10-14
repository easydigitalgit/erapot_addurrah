<?php
require __DIR__ . '/../backend/vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$stmt = $pdo->prepare("SELECT ar.*, s.nis, s.nama_lengkap FROM anggota_rombel ar JOIN siswa s ON s.id = ar.siswa_id WHERE ar.rombel_id = 74");
$stmt->execute();
$count = 0;
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    echo "AR ID: {$r['id']} | Siswa ID: {$r['siswa_id']} | NIS: {$r['nis']} | TA: {$r['tahun_ajaran_id']} | Sem: {$r['semester']} | Nama: {$r['nama_lengkap']}\n";
}
echo "Total in rombel 74: $count\n";
