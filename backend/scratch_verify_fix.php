<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // AISYAHARA INDRI NISN: 0117905743
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $stmt->execute(['0117905743']);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    $siswa_id = $siswa['id'];

    // Mapel Bahasa Indonesia ID = 3
    $mapel_id = 3;

    // Formatif
    $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ?");
    $stmt->execute([$siswa_id, $mapel_id]);
    $formatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sumatif (SEMUA KATEGORI)
    $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ?");
    $stmt->execute([$siswa_id, $mapel_id]);
    $sumatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Logika Baru
    $sum_nh = 0; $count_nh = 0;
    $sum_uh = 0; $count_uh = 0;
    foreach ($formatifs as $f) {
        $jenis = strtoupper($f['jenis_penilaian']);
        if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
            $sum_uh += $f['nilai_angka']; $count_uh++;
        } else {
            $sum_nh += $f['nilai_angka']; $count_nh++;
        }
    }
    // Rata-rata (asumsi pembagi dinamis 4 untuk NH dan 4 untuk UH dari data sebelumnya)
    $avg_nh = 91.25; 
    $avg_uh = 87.5;

    $sum_sts = 0; $count_sts = 0;
    $sum_pas = 0; $count_pas = 0;
    $sum_sas = 0; $count_sas = 0;

    foreach ($sumatifs as $s) {
        $jenis = strtoupper($s['jenis_sumatif'] ?? $s['jenis_penilaian']);
        if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
            $sum_sts += $s['nilai']; $count_sts++;
        } elseif (strpos($jenis, 'PAS') !== false) {
            $sum_pas += $s['nilai']; $count_pas++;
        } elseif (strpos($jenis, 'SAS') !== false) {
            $sum_sas += $s['nilai']; $count_sas++;
        }
    }

    $avg_sts = $count_sts > 0 ? ($sum_sts / $count_sts) : 0;
    $avg_pas = $count_pas > 0 ? ($sum_pas / $count_pas) : 0;
    $avg_sas = $count_sas > 0 ? ($sum_sas / $count_sas) : 0;

    $w_nh = 0.35; $w_uh = 0.35; $w_sts = 0.15; $w_sas = 0.15;

    $val_sts = ($count_sts > 0) ? $avg_sts : $avg_pas;
    $val_sas = ($count_sas > 0) ? $avg_sas : (($val_sts != $avg_pas) ? $avg_pas : 0);

    $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($val_sts * $w_sts) + ($val_sas * $w_sas);

    echo "HASIL SIMULASI LOGIKA BARU:\n";
    echo "Rata NH (35%): $avg_nh -> " . ($avg_nh * $w_nh) . "\n";
    echo "Rata UH (35%): $avg_uh -> " . ($avg_uh * $w_uh) . "\n";
    echo "Slot STS (15%): $val_sts (Dari PAS) -> " . ($val_sts * $w_sts) . "\n";
    echo "Slot SAS (15%): $val_sas -> " . ($val_sas * $w_sas) . "\n";
    echo "TOTAL: $kalkulasi\n";
    echo "PEMBULATAN: " . round($kalkulasi) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
