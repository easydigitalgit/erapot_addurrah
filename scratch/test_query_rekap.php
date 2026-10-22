<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');

$sql = "
SELECT 
    r.id AS rombel_id,
    CONCAT(r.tingkat, ' ', r.nama_rombel) AS nama_kelas,
    COUNT(ar.id) AS total_anggota,
    SUM(CASE WHEN s.status_siswa = 'Aktif' THEN 1 ELSE 0 END) AS siswa_aktif,
    SUM(CASE WHEN s.status_siswa = 'Pindah' THEN 1 ELSE 0 END) AS siswa_pindah,
    SUM(CASE WHEN s.id IS NULL THEN 1 ELSE 0 END) AS siswa_hilang_orphan
FROM rombel r
JOIN anggota_rombel ar ON r.id = ar.rombel_id AND ar.tahun_ajaran_id = r.id_tahun_ajaran
LEFT JOIN siswa s ON ar.siswa_id = s.id
WHERE r.id_tahun_ajaran = 10
GROUP BY r.id, r.tingkat, r.nama_rombel
ORDER BY FIELD(r.tingkat, 'VII', 'VIII', 'IX'), r.nama_rombel;
";

$stmt = $pdo->query($sql);
echo sprintf("%-4s | %-16s | %-13s | %-11s | %-12s | %-12s\n", "ID", "Kelas", "Total Anggota", "Siswa Aktif", "Siswa Pindah", "Siswa Orphan");
echo str_repeat("-", 80) . "\n";
$sumAktif = 0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo sprintf("%-4d | %-16s | %-13d | %-11d | %-12d | %-12d\n", 
        $row['rombel_id'], 
        $row['nama_kelas'], 
        $row['total_anggota'], 
        $row['siswa_aktif'], 
        $row['siswa_pindah'], 
        $row['siswa_hilang_orphan']
    );
    $sumAktif += $row['siswa_aktif'];
}
echo str_repeat("-", 80) . "\n";
echo "TOTAL SISWA AKTIF KESELURUHAN: $sumAktif\n";
