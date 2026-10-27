<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== 1. CEK ANGGOTA ROMBEL KELAS IX (TA 10) ===" . PHP_EOL;
$sqlRombelIX = "
    SELECT r.id, r.nama_rombel, r.tingkat, COUNT(ar.siswa_id) as total_anggota, COUNT(DISTINCT ar.siswa_id) as total_unik
    FROM rombel r
    LEFT JOIN anggota_rombel ar ON ar.rombel_id = r.id AND ar.tahun_ajaran_id = 10
    WHERE r.tingkat = 'IX'
    GROUP BY r.id, r.nama_rombel, r.tingkat
";
$rombelsIX = $pdo->query($sqlRombelIX)->fetchAll(PDO::FETCH_ASSOC);
foreach ($rombelsIX as $r) {
    if ($r['total_anggota'] > 0) {
        echo "- Rombel {$r['nama_rombel']} (ID {$r['id']}): Total = {$r['total_anggota']}, Unik = {$r['total_unik']}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== 2. CEK DUPLIKASI NILAI FORMATIF DI SELURUH TINGKAT (TA 10) ===" . PHP_EOL;
$sqlDupF = "
    SELECT tingkat, COUNT(*) as total_duplikat_group
    FROM (
        SELECT r.tingkat, nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan
        FROM nilai_formatif nf
        JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id AND ar.tahun_ajaran_id = 10
        JOIN rombel r ON r.id = ar.rombel_id
        WHERE nf.tahun_ajaran_id = 10
        GROUP BY r.tingkat, nf.siswa_id, nf.mapel_id, nf.kategori, nf.jenis_penilaian, nf.pertemuan
        HAVING COUNT(*) > 1
    ) sub
    GROUP BY tingkat
";
$dupsF = $pdo->query($sqlDupF)->fetchAll(PDO::FETCH_ASSOC);
if (empty($dupsF)) {
    echo "Tidak ada duplikasi nilai formatif di TA 10 (Semua tingkat 0 duplikat).\n";
} else {
    print_r($dupsF);
}

echo PHP_EOL . "=== 3. CEK DUPLIKASI NILAI SUMATIF DI SELURUH TINGKAT (TA 10) ===" . PHP_EOL;
$sqlDupS = "
    SELECT tingkat, COUNT(*) as total_duplikat_group
    FROM (
        SELECT r.tingkat, ns.siswa_id, ns.mapel_id, IFNULL(ns.kategori, '') as kat, ns.jenis_sumatif
        FROM nilai_sumatif ns
        JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = 10
        JOIN rombel r ON r.id = ar.rombel_id
        WHERE ns.tahun_ajaran_id = 10
        GROUP BY r.tingkat, ns.siswa_id, ns.mapel_id, IFNULL(ns.kategori, ''), ns.jenis_sumatif
        HAVING COUNT(*) > 1
    ) sub
    GROUP BY tingkat
";
$dupsS = $pdo->query($sqlDupS)->fetchAll(PDO::FETCH_ASSOC);
print_r($dupsS);

