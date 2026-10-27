<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

foreach (['ATHALLAH', 'FAIZ', 'HABIBSYAH'] as $kw) {
    echo "Pencarian '$kw':\n";
    $rows = $pdo->query("SELECT s.id, s.nama_lengkap, r.nama_rombel FROM siswa s LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 LEFT JOIN rombel r ON r.id = ar.rombel_id WHERE s.nama_lengkap LIKE '%$kw%'")->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
}

