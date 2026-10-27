<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== ANALISIS NILAI_RAPOR SELURUH SEKOLAH (TA 10 - Ganjil, Tengah Semester) ===" . PHP_EOL;

// 1. Cek berapa total siswa aktif di TA 10
$total_siswa = $pdo->query("SELECT COUNT(DISTINCT siswa_id) FROM anggota_rombel WHERE tahun_ajaran_id = 10")->fetchColumn();
echo "Total Siswa Aktif di TA 10: $total_siswa" . PHP_EOL;

// 2. Cek rombel dan tingkat
$rombels = $pdo->query("
    SELECT r.id, r.nama_rombel, r.tingkat, COUNT(ar.siswa_id) as total_siswa
    FROM rombel r
    JOIN anggota_rombel ar ON ar.rombel_id = r.id AND ar.tahun_ajaran_id = 10
    GROUP BY r.id, r.nama_rombel, r.tingkat
    ORDER BY r.tingkat, r.nama_rombel
")->fetchAll(PDO::FETCH_ASSOC);

echo "\nRombel Aktif TA 10:\n";
foreach ($rombels as $r) {
    echo "- [ID {$r['id']}] {$r['tingkat']} - {$r['nama_rombel']}: {$r['total_siswa']} siswa\n";
}

// 3. Cek mata pelajaran yang nilainya sudah ada di formatif/sumatif tetapi belum masuk ke nilai_rapor
$missing_rapor = $pdo->query("
    SELECT r.tingkat, r.nama_rombel, m.id as mapel_id, m.nama_mapel,
           COUNT(DISTINCT nf.siswa_id) as siswa_ada_formatif,
           COUNT(DISTINCT ns.siswa_id) as siswa_ada_sumatif,
           COUNT(DISTINCT nr.siswa_id) as siswa_ada_rapor
    FROM rombel r
    JOIN anggota_rombel ar ON ar.rombel_id = r.id AND ar.tahun_ajaran_id = 10
    JOIN siswa s ON s.id = ar.siswa_id
    CROSS JOIN mata_pelajaran m
    LEFT JOIN nilai_formatif nf ON nf.siswa_id = s.id AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = 10 AND nf.kategori = 'Tengah Semester'
    LEFT JOIN nilai_sumatif ns ON ns.siswa_id = s.id AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = 10 AND ns.kategori = 'Tengah Semester'
    LEFT JOIN nilai_rapor nr ON nr.siswa_id = s.id AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = 10 AND nr.kategori = 'Tengah Semester'
    WHERE m.status = 'Aktif'
    GROUP BY r.tingkat, r.nama_rombel, m.id, m.nama_mapel
    HAVING (siswa_ada_formatif > 0 OR siswa_ada_sumatif > 0) AND siswa_ada_rapor < siswa_ada_formatif
    ORDER BY r.tingkat, r.nama_rombel, m.id
")->fetchAll(PDO::FETCH_ASSOC);

echo "\nDAFTAR MAPEL DENGAN NILAI FORMATIF/SUMATIF LENGKAP TAPI NILAI_RAPOR KOSONG / BELUM DISINKRON:\n";
foreach ($missing_rapor as $row) {
    echo sprintf(
        "- %-4s %-12s | Mapel [%2d] %-35s : Formatif=%2d | Sumatif=%2d | Rapor=%2d (KOSONG: %2d)\n",
        $row['tingkat'], $row['nama_rombel'], $row['mapel_id'], $row['nama_mapel'],
        $row['siswa_ada_formatif'], $row['siswa_ada_sumatif'], $row['siswa_ada_rapor'],
        ($row['siswa_ada_formatif'] - $row['siswa_ada_rapor'])
    );
}
