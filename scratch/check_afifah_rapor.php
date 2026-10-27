<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// Cek Afifah Dhia Fadillah (Siswa 1 di 8 INTAN)
$s = $pdo->query("SELECT nr.* FROM nilai_rapor nr JOIN siswa s ON s.id = nr.siswa_id WHERE s.nama_lengkap LIKE '%AFIFAH DHIA%' AND nr.mapel_id = 1 AND nr.tahun_ajaran_id = 10 AND nr.kategori = 'Tengah Semester'")->fetch(PDO::FETCH_ASSOC);
echo "Afifah Dhia (8 Intan) di nilai_rapor:\n";
print_r($s);

