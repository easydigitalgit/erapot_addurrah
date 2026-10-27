<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');

// Check Carnelian (80) & Ruby (81)
$stmt = $pdo->query("SELECT siswa_id FROM anggota_rombel WHERE rombel_id IN (80, 81)");
$akhwat_ids = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $akhwat_ids[$row['siswa_id']] = true;
}
echo "Total siswi Akhwat (Carnelian & Ruby): " . count($akhwat_ids) . "\n";

// In current DB:
$stmt = $pdo->query("SELECT r.nama_rombel, m.nama_mapel, count(nr.id) as cnt_rapor, count(ns.id) as cnt_sumatif
    FROM rombel r
    JOIN anggota_rombel ar ON ar.rombel_id = r.id
    LEFT JOIN nilai_rapor nr ON nr.siswa_id = ar.siswa_id AND nr.mapel_id IN (10, 44) AND nr.kategori = 'Tengah Semester'
    LEFT JOIN nilai_sumatif ns ON ns.siswa_id = ar.siswa_id AND ns.mapel_id IN (10, 44) AND ns.kategori = 'Tengah Semester'
    LEFT JOIN mata_pelajaran m ON m.id = nr.mapel_id OR m.id = ns.mapel_id
    WHERE r.id IN (77, 78, 79, 80, 81)
    GROUP BY r.id, r.nama_rombel, m.nama_mapel");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Rombel: {$row['nama_rombel']} | Mapel: {$row['nama_mapel']} | Rapor: {$row['cnt_rapor']} | Sumatif: {$row['cnt_sumatif']}\n";
}

