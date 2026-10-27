<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$ids = [18, 427, 54, 430, 451, 40];
$rows = $pdo->query("SELECT id, nis, nisn, nama_lengkap FROM siswa WHERE id IN (" . implode(',', $ids) . ")")->fetchAll(PDO::FETCH_ASSOC);
echo "Verifikasi 3 kasus duplikat sebelumnya:\n";
foreach ($rows as $r) {
    echo "- [ID {$r['id']}] {$r['nama_lengkap']} | NIS Baru: '{$r['nis']}' | NISN: '{$r['nisn']}'\n";
}

