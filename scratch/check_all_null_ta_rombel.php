<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== DAFTAR SEMUA ROMBEL TANPA TAHUN AJARAN (NULL) DAN JUMLAH SISWANYA ===\n";
$stmt = $pdo->query("
    SELECT r.id, r.nama_rombel, r.tingkat, r.wali_kelas_id, gt.nama_lengkap as nama_wali,
           (SELECT COUNT(*) FROM siswa s WHERE s.rombel_id = r.id) as total_siswa_table,
           (SELECT COUNT(*) FROM anggota_rombel ar WHERE ar.rombel_id = r.id) as total_ar_table
    FROM rombel r
    LEFT JOIN guru_tendik gt ON gt.id = r.wali_kelas_id
    WHERE r.id_tahun_ajaran IS NULL OR r.id_tahun_ajaran = 0
    ORDER BY r.tingkat, r.nama_rombel
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    echo "ID: {$row['id']} | Tingkat: {$row['tingkat']} | Nama: {$row['nama_rombel']} | Wali: {$row['nama_wali']} | Siswa di siswa.rombel_id: {$row['total_siswa_table']} | Siswa di anggota_rombel: {$row['total_ar_table']}\n";
}
