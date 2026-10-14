<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== DETAIL DATA 7 SISWA (ROMBEL 55) ===\n";
$q = $pdo->query("SELECT s.id, s.nama_lengkap, s.nis, s.nisn, s.rombel_id, s.diterima_dikelas, ar.rombel_id as ar_rombel, ar.tahun_ajaran_id, ar.created_at as ar_created FROM siswa s LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id WHERE s.rombel_id = 55 ORDER BY s.id");
while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Nama: {$r['nama_lengkap']} | NIS: {$r['nis']} | NISN: {$r['nisn']} | Rombel: {$r['rombel_id']} | AR_Rombel: {$r['ar_rombel']} | TA: {$r['tahun_ajaran_id']} | Created: {$r['ar_created']} | Diterima: {$r['diterima_dikelas']}\n";
}

echo "\n=== DETAIL DATA BEBERAPA SISWA DI ROMBEL 80 ===\n";
$q = $pdo->query("SELECT s.id, s.nama_lengkap, s.nis, s.nisn, s.rombel_id, s.diterima_dikelas, ar.rombel_id as ar_rombel, ar.tahun_ajaran_id, ar.created_at as ar_created FROM siswa s LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id WHERE s.rombel_id = 80 ORDER BY s.id LIMIT 10");
while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Nama: {$r['nama_lengkap']} | NIS: {$r['nis']} | NISN: {$r['nisn']} | Rombel: {$r['rombel_id']} | AR_Rombel: {$r['ar_rombel']} | TA: {$r['tahun_ajaran_id']} | Created: {$r['ar_created']} | Diterima: {$r['diterima_dikelas']}\n";
}

echo "\n=== BANDINGKAN ROMBEL 55 VS 80 ===\n";
$r55 = $pdo->query("SELECT * FROM rombel WHERE id = 55")->fetch(PDO::FETCH_ASSOC);
$r80 = $pdo->query("SELECT * FROM rombel WHERE id = 80")->fetch(PDO::FETCH_ASSOC);
print_r(['rombel_55' => $r55, 'rombel_80' => $r80]);

