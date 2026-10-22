<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$stmt = $pdo->query('SELECT * FROM tahun_ajaran');
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Tahun: {$r['tahun']} | Semester: {$r['semester']} | Status: {$r['is_active']}\n";
}

echo "\n--- 15 ROMBEL TA AKTIF (TA ID 10) ---\n";
$stmt = $pdo->query('
    SELECT 
        r.id as rombel_id,
        CONCAT(r.tingkat, " ", r.nama_rombel) as nama_kelas,
        r.tingkat,
        r.nama_rombel,
        COUNT(ar.id) as total_siswa
    FROM rombel r
    JOIN anggota_rombel ar ON r.id = ar.rombel_id AND ar.tahun_ajaran_id = r.id_tahun_ajaran
    JOIN siswa s ON ar.siswa_id = s.id
    WHERE r.id_tahun_ajaran = 10 AND s.status_siswa = "Aktif"
    GROUP BY r.id, r.tingkat, r.nama_rombel
    ORDER BY FIELD(r.tingkat, "VII", "VIII", "IX"), r.nama_rombel
');
$sum = 0;
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo sprintf("%-4s | %-16s | %2d siswa\n", $r['rombel_id'], $r['nama_kelas'], $r['total_siswa']);
    $sum += $r['total_siswa'];
}
echo "TOTAL KESELURUHAN: $sum siswa\n";
