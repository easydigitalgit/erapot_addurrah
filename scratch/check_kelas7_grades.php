<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// 1. Cek apakah ada record ganda di nilai_formatif untuk siswa kelas 7
$sqlFormatif = "
    SELECT nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan, COUNT(*) as c
    FROM nilai_formatif nf
    JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII'
    GROUP BY nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan
    HAVING c > 1
";
$dupFormatif = $pdo->query($sqlFormatif)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikasi nilai formatif siswa kelas VII: " . count($dupFormatif) . PHP_EOL;

// 2. Cek apakah ada record ganda di nilai_sumatif untuk siswa kelas 7
$sqlSumatif = "
    SELECT ns.siswa_id, ns.mapel_id, ns.kategori, ns.jenis_sumatif, COUNT(*) as c
    FROM nilai_sumatif ns
    JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII'
    GROUP BY ns.siswa_id, ns.mapel_id, ns.kategori, ns.jenis_sumatif
    HAVING c > 1
";
$dupSumatif = $pdo->query($sqlSumatif)->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikasi nilai sumatif siswa kelas VII: " . count($dupSumatif) . PHP_EOL;

// 3. Cek jumlah nilai yang dimiliki siswa kelas VII
$countFormatif = $pdo->query("
    SELECT COUNT(*) FROM nilai_formatif nf
    JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII'
")->fetchColumn();

$countSumatif = $pdo->query("
    SELECT COUNT(*) FROM nilai_sumatif ns
    JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id
    JOIN rombel r ON r.id = ar.rombel_id
    WHERE r.tingkat = 'VII'
")->fetchColumn();

echo "Total nilai formatif tersimpan untuk kelas VII: " . $countFormatif . PHP_EOL;
echo "Total nilai sumatif tersimpan untuk kelas VII: " . $countSumatif . PHP_EOL;

