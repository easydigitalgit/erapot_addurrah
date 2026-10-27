<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// Siswa yang benar-benar aktif di kelas VII pada Tahun Ajaran 10:
$sql = "
    SELECT r.id as rombel_id, r.nama_rombel, COUNT(ar.siswa_id) as total_siswa
    FROM rombel r
    JOIN anggota_rombel ar ON ar.rombel_id = r.id AND ar.tahun_ajaran_id = 10
    WHERE r.tingkat = 'VII'
    GROUP BY r.id, r.nama_rombel
";
$rombels = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
echo "Rombel Kelas VII TA 10:\n";
print_r($rombels);

// Sekarang cek apakah ada duplikasi nilai untuk siswa KELAS VII TA 10 saat ini:
$sqlFormatif = "
    SELECT nf.siswa_id, s.nama_lengkap, nf.mapel_id, COUNT(*) as c
    FROM nilai_formatif nf
    JOIN siswa s ON s.id = nf.siswa_id
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII' AND nf.tahun_ajaran_id = 10
    GROUP BY nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan
    HAVING c > 1
";
$dupF = $pdo->query($sqlFormatif)->fetchAll(PDO::FETCH_ASSOC);
echo "\nDuplikat Nilai Formatif Siswa Kelas VII TA 10: " . count($dupF) . PHP_EOL;

$sqlSumatif = "
    SELECT ns.siswa_id, s.nama_lengkap, ns.mapel_id, COUNT(*) as c
    FROM nilai_sumatif ns
    JOIN siswa s ON s.id = ns.siswa_id
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII' AND ns.tahun_ajaran_id = 10
    GROUP BY ns.siswa_id, ns.mapel_id, IFNULL(ns.kategori, ''), ns.jenis_sumatif
    HAVING c > 1
";
$dupS = $pdo->query($sqlSumatif)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikat Nilai Sumatif Siswa Kelas VII TA 10: " . count($dupS) . PHP_EOL;
if (count($dupS) > 0) {
    print_r($dupS);
}

