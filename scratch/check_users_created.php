<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$rows = $pdo->query("SELECT u.id as uid, u.username, u.created_at as u_created, s.id as sid, s.nama_lengkap, s.rombel_id, ar.created_at as ar_created FROM siswa s JOIN users u ON u.id = s.user_id LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id WHERE s.rombel_id IN (55, 80) ORDER BY s.id ASC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "Siswa: {$r['nama_lengkap']} | User Created: {$r['u_created']} | AR Created: {$r['ar_created']}\n";
}
