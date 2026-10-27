<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$cnt = $pdo->query('
    SELECT COUNT(*) 
    FROM raporsmpit_vps.anggota_rombel ar1
    INNER JOIN raporsmpit_vps.anggota_rombel ar2 
    WHERE ar1.id > ar2.id 
      AND ar1.rombel_id = ar2.rombel_id 
      AND ar1.siswa_id = ar2.siswa_id
')->fetchColumn();
echo "Duplicate rows to be removed from raporsmpit_vps.anggota_rombel: $cnt\n";

$cntLoc = $pdo->query('
    SELECT COUNT(*) 
    FROM raporsmpit.anggota_rombel ar1
    INNER JOIN raporsmpit.anggota_rombel ar2 
    WHERE ar1.id > ar2.id 
      AND ar1.rombel_id = ar2.rombel_id 
      AND ar1.siswa_id = ar2.siswa_id
')->fetchColumn();
echo "Duplicate rows to be removed from raporsmpit.anggota_rombel: $cntLoc\n";

