<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$ids = [12, 15, 26, 41, 45, 58, 119, 120, 121, 122, 123, 125, 423, 425];
$rows = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa WHERE id IN (" . implode(',', $ids) . ")")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "ID {$r['id']}: {$r['nama_lengkap']} | NIS: '{$r['nis']}' | NISN: '{$r['nisn']}'\n";
}

