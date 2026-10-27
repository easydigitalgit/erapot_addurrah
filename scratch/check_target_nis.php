<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

foreach (['09.25.0080', '09.25.0088', '09.25.0044'] as $nis) {
    echo "Siswa dengan NIS '$nis' saat ini di DB:\n";
    $rows = $pdo->query("SELECT id, nis, nama_lengkap FROM siswa WHERE nis = '$nis'")->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
}

