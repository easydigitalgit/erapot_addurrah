<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

foreach ([77 => 'Amazonite', 78 => 'Opal', 79 => 'Zamrud', 80 => 'Carnelian', 81 => 'Ruby'] as $rid => $rname) {
    $stmt = $pdo->query("SELECT s.jenis_kelamin, COUNT(*) as c FROM siswa s JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = $rid GROUP BY s.jenis_kelamin");
    $genders = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $genders[] = "{$row['jenis_kelamin']}: {$row['c']}";
    }
    echo "Rombel VII $rname: " . implode(', ', $genders) . "\n";
}

