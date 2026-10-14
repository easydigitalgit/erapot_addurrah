<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== PERBANDINGAN DATA SISWA 55 VS 80 ===\n";
$s7 = $pdo->query("SELECT id, nama_lengkap, nis, nisn, rombel_id, diterima_dikelas, tgl_diterima FROM siswa WHERE rombel_id = 55")->fetchAll(PDO::FETCH_ASSOC);
$s26 = $pdo->query("SELECT id, nama_lengkap, nis, nisn, rombel_id, diterima_dikelas, tgl_diterima FROM siswa WHERE rombel_id = 80 LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

echo "Contoh 7 siswa (Rombel 55):\n";
print_r($s7);
echo "Contoh 26 siswa (Rombel 80):\n";
print_r($s26);
