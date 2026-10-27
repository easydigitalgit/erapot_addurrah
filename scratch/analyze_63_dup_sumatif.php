<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$sql = "
    SELECT r.tingkat, r.nama_rombel, s.id as siswa_id, s.nama_lengkap, m.nama_mapel, 
           ns.id as sumatif_id, ns.nilai, ns.jenis_sumatif, ns.kategori, ns.created_at, ns.updated_at
    FROM nilai_sumatif ns
    JOIN siswa s ON s.id = ns.siswa_id
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10
    JOIN rombel r ON r.id = ar.rombel_id
    JOIN mata_pelajaran m ON m.id = ns.mapel_id
    WHERE ns.tahun_ajaran_id = 10
      AND (ns.siswa_id, ns.mapel_id, IFNULL(ns.kategori, ''), ns.jenis_sumatif) IN (
          SELECT siswa_id, mapel_id, IFNULL(kategori, ''), jenis_sumatif 
          FROM nilai_sumatif 
          WHERE tahun_ajaran_id = 10
          GROUP BY siswa_id, mapel_id, IFNULL(kategori, ''), jenis_sumatif 
          HAVING COUNT(*) > 1
      )
    ORDER BY r.tingkat, r.nama_rombel, s.nama_lengkap, m.nama_mapel, ns.id
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$groups = [];
foreach ($rows as $row) {
    $key = "{$row['tingkat']}|{$row['nama_rombel']}|{$row['siswa_id']}|{$row['nama_lengkap']}|{$row['nama_mapel']}|{$row['jenis_sumatif']}";
    $groups[$key][] = $row;
}

echo "Total grup nilai sumatif ganda di TA 10: " . count($groups) . PHP_EOL;

$sample_count = 0;
foreach ($groups as $key => $items) {
    $sample_count++;
    if ($sample_count <= 10 || $sample_count > count($groups) - 5) {
        echo "\n[$key]:\n";
        foreach ($items as $item) {
            echo "  - ID {$item['sumatif_id']}: Nilai = {$item['nilai']}, Kategori = '{$item['kategori']}', Created = {$item['created_at']}\n";
        }
    }
}

