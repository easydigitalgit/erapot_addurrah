<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// 1. Cek duplikasi di tahun ajaran aktif (tahun_ajaran_id = 10)
$sqlFormatif10 = "
    SELECT nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan, COUNT(*) as c
    FROM nilai_formatif nf
    JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII' AND nf.tahun_ajaran_id = 10
    GROUP BY nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan
    HAVING c > 1
";
$dupFormatif10 = $pdo->query($sqlFormatif10)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikasi nilai formatif siswa kelas VII (Tahun Ajaran 10): " . count($dupFormatif10) . PHP_EOL;

$sqlSumatif10 = "
    SELECT ns.siswa_id, ns.mapel_id, ns.kategori, ns.jenis_sumatif, COUNT(*) as c
    FROM nilai_sumatif ns
    JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII' AND ns.tahun_ajaran_id = 10
    GROUP BY ns.siswa_id, ns.mapel_id, ns.kategori, ns.jenis_sumatif
    HAVING c > 1
";
$dupSumatif10 = $pdo->query($sqlSumatif10)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikasi nilai sumatif siswa kelas VII (Tahun Ajaran 10): " . count($dupSumatif10) . PHP_EOL;

// Sekarang cek specifically siswa Ruby (81) dan Carnelian (80) yang kemarin dobel di anggota_rombel
$sqlRubyCarnelian = "
    SELECT r.nama_rombel, COUNT(DISTINCT ar.siswa_id) as total_siswa
    FROM rombel r
    JOIN anggota_rombel ar ON ar.rombel_id = r.id
    WHERE r.id IN (80, 81)
    GROUP BY r.id, r.nama_rombel
";
$rc = $pdo->query($sqlRubyCarnelian)->fetchAll(PDO::FETCH_ASSOC);
print_r($rc);

