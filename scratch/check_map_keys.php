<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$dbRombel = $pdo->query("SELECT * FROM rombel")->fetchAll(PDO::FETCH_ASSOC);
$mapRombel = [];
$history = [];
foreach ($dbRombel as $r) {
    $nama = strtolower(trim($r['nama_rombel']));
    $tingkat = strtolower(trim($r['tingkat']));

    $keys = [
        $tingkat . ' ' . $nama,
        $tingkat . '-' . $nama,
        $tingkat . $nama,
        $nama
    ];
    foreach ($keys as $k) {
        $history[$k][] = "ID: {$r['id']} (Tingkat: {$r['tingkat']}, TA: " . ($r['id_tahun_ajaran'] ?? 'NULL') . ")";
        $mapRombel[$k] = $r['id'];
    }
}

echo "=== MAPPING KUNCI UNTUK CARNELIAN ===\n";
foreach ($history as $k => $h) {
    if (strpos($k, 'carnelian') !== false) {
        echo "Key: '$k'\n";
        echo "  Urutan Timpa:\n    " . implode("\n    ", $h) . "\n";
        echo "  Nilai Akhir di \$mapRombel: {$mapRombel[$k]}\n\n";
    }
}
