<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// Inspect duplicate sumatif rows
$sql = "
    SELECT ns.id, ns.siswa_id, s.nama_lengkap, ns.mapel_id, ns.kategori, ns.jenis_sumatif, ns.nilai, ns.created_at, ns.updated_at
    FROM nilai_sumatif ns
    JOIN siswa s ON s.id = ns.siswa_id
    WHERE ns.siswa_id IN (
        SELECT siswa_id FROM nilai_sumatif 
        GROUP BY siswa_id, mapel_id, kategori, jenis_sumatif 
        HAVING COUNT(*) > 1
    )
    ORDER BY ns.siswa_id, ns.mapel_id, ns.kategori, ns.jenis_sumatif, ns.id
    LIMIT 20
";
$stmt = $pdo->query($sql);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($row) . PHP_EOL;
}

