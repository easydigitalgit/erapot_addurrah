<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$siswa_id = 33; // Adabi Limansyah Sagala
$rombel_id = 73; // Topaz
$ta_id = 10;
$kategori = 'Tengah Semester';
$semester = 'Ganjil';
$tingkatClean = 8;

// Bobot
$bobot = [
    'nh' => 0.35,
    'uh' => 0.35,
    'sts' => 0.30
];

// Ambil aturan predikat
$aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC")->fetchAll(PDO::FETCH_ASSOC);

// Test untuk Mapel 1 (PAI) dan Mapel 3 (B. Indonesia)
foreach ([1 => 'PAI', 3 => 'B. Indonesia'] as $mapel_id => $nama_mapel) {
    echo "--- SIMULASI MAPEL $nama_mapel (ID $mapel_id) ---\n";

    // Formatif
    $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ?");
    $stmt->execute([$siswa_id, $mapel_id, $ta_id, $kategori]);
    $formatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sumatif
    $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ?");
    $stmt->execute([$siswa_id, $mapel_id, $ta_id, $kategori]);
    $sumatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sum_nh = 0; $sum_uh = 0;
    $max_nh = 0; $max_uh = 0;
    $lm_scores = [];

    foreach ($formatifs as $f) {
        $jenis = strtoupper(trim($f['jenis_penilaian']));
        $nilai = (float)$f['nilai_angka'];
        $pert = (int)$f['pertemuan'];

        if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
            $sum_uh += $nilai;
            if ($pert > $max_uh) $max_uh = $pert;
        } else {
            $sum_nh += $nilai;
            if ($pert > $max_nh) $max_nh = $pert;
        }

        if (!isset($lm_scores[$pert])) $lm_scores[$pert] = ['sum' => 0, 'count' => 0];
        $lm_scores[$pert]['sum'] += $nilai;
        $lm_scores[$pert]['count']++;
    }

    $sum_sts = 0; $count_sts = 0;
    foreach ($sumatifs as $s) {
        $jenis = strtoupper(trim($s['jenis_sumatif']));
        $nilai = (float)$s['nilai'];
        if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
            $sum_sts += $nilai;
            $count_sts++;
        }
    }

    $pembagi_nh = $max_nh > 0 ? $max_nh : 1;
    $pembagi_uh = $max_uh > 0 ? $max_uh : 1;

    $avg_nh = $sum_nh / $pembagi_nh;
    $avg_uh = $sum_uh / $pembagi_uh;
    $avg_sts = $count_sts > 0 ? ($sum_sts / $count_sts) : 0;

    $kalkulasi = ($avg_nh * $bobot['nh']) + ($avg_uh * $bobot['uh']) + ($avg_sts * $bobot['sts']);
    $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
    $rata_sumatif = round($avg_sts, 1);
    $nilai_akhir = number_format($kalkulasi, 1, '.', '');

    $predikat = '-';
    foreach ($aturanPredikat as $aturan) {
        if (floor($nilai_akhir) >= $aturan['nilai_min'] && floor($nilai_akhir) <= $aturan['nilai_max']) {
            $predikat = $aturan['deskripsi_predikat'];
            break;
        }
    }

    echo "  Formatif NH: sum=$sum_nh, count=$pembagi_nh, avg=$avg_nh\n";
    echo "  Formatif UH: sum=$sum_uh, count=$pembagi_uh, avg=$avg_uh\n";
    echo "  Sumatif STS: sum=$sum_sts, count=$count_sts, avg=$avg_sts\n";
    echo "  => Nilai Akhir Kalkulasi: $nilai_akhir\n";
    echo "  => Rata Formatif: $rata_formatif\n";
    echo "  => Rata Sumatif: $rata_sumatif\n";
    echo "  => Predikat: $predikat\n";
}
