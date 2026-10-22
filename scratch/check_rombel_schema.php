<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query('SHOW COLUMNS FROM rombel');
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}
echo "\n--- SAMPLE DATA ROMBEL DI TA 10 ---\n";
$stmt = $pdo->query('
    SELECT r.id, r.nama_rombel, r.tingkat, r.tahun_ajaran_id, COUNT(ar.id) as jml
    FROM rombel r
    LEFT JOIN anggota_rombel ar ON r.id = ar.rombel_id AND ar.tahun_ajaran_id = 10
    GROUP BY r.id
    HAVING jml > 0
    ORDER BY r.tingkat, r.nama_rombel
');
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Tingkat: {$r['tingkat']} | Nama: {$r['nama_rombel']} | TA: {$r['tahun_ajaran_id']} | Siswa: {$r['jml']}\n";
}
