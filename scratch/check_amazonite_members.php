<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=raporsmpit;charset=utf8mb4", "root", "");

$stmt = $pdo->query("SELECT s.id, s.nis, s.nisn, s.nama_lengkap, ar.rombel_id 
    FROM anggota_rombel ar 
    JOIN siswa s ON s.id = ar.siswa_id 
    WHERE ar.rombel_id = 77 
    ORDER BY s.nama_lengkap ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total anggota_rombel for rombel 77: " . count($rows) . "\n";
foreach ($rows as $idx => $r) {
    echo sprintf("%2d. ID: %-4s | NIS: %-15s | NISN: %-12s | Nama: %s\n", 
        $idx + 1, $r['id'], $r['nis'], $r['nisn'], $r['nama_lengkap']);
}
