<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
echo "Total siswa          : " . $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn() . PHP_EOL;
echo "Total anggota_rombel : " . $pdo->query("SELECT COUNT(*) FROM anggota_rombel")->fetchColumn() . PHP_EOL;
echo "Total master_lm      : " . $pdo->query("SELECT COUNT(*) FROM master_lm")->fetchColumn() . PHP_EOL;
echo "Total nilai_formatif : " . $pdo->query("SELECT COUNT(*) FROM nilai_formatif")->fetchColumn() . PHP_EOL;
echo "Total nilai_sumatif  : " . $pdo->query("SELECT COUNT(*) FROM nilai_sumatif")->fetchColumn() . PHP_EOL;
echo "Duplikat sumatif     : " . $pdo->query("SELECT COUNT(*) FROM (SELECT siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, ''), jenis_sumatif FROM nilai_sumatif GROUP BY siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, ''), jenis_sumatif HAVING COUNT(*) > 1) t")->fetchColumn() . PHP_EOL;

