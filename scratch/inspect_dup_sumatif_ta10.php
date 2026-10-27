<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$sql = "
    SELECT ns.id, ns.siswa_id, s.nama_lengkap, r.nama_rombel, ns.mapel_id, m.nama_mapel, ns.kategori, ns.jenis_sumatif, ns.nilai, ns.created_at, ns.updated_at
    FROM nilai_sumatif ns
    JOIN siswa s ON s.id = ns.siswa_id
    JOIN anggota_rombel ar ON ar.siswa_id = s.id
    JOIN rombel r ON r.id = ar.rombel_id
    JOIN mata_pelajaran m ON m.id = ns.mapel_id
    WHERE r.tingkat = 'VII' AND ns.tahun_ajaran_id = 10
      AND (ns.siswa_id, ns.mapel_id, IFNULL(ns.kategori, ''), ns.jenis_sumatif) IN (
          SELECT siswa_id, mapel_id, IFNULL(kategori, ''), jenis_sumatif 
          FROM nilai_sumatif 
          WHERE tahun_ajaran_id = 10
          GROUP BY siswa_id, mapel_id, IFNULL(kategori, ''), jenis_sumatif 
          HAVING COUNT(*) > 1
      )
    ORDER BY ns.siswa_id, ns.mapel_id, ns.id
    LIMIT 20
";
$stmt = $pdo->query($sql);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($row) . PHP_EOL;
}

