<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$ta_id = 10;
$kategori = 'Tengah Semester';

$bobotRows = $pdo->query("SELECT * FROM setting_bobot_nilai WHERE kategori = 'tengah_semester'")->fetchAll(PDO::FETCH_ASSOC);
$bobot = ['nh' => 0.35, 'uh' => 0.35, 'sts' => 0.30];
foreach ($bobotRows as $b) {
    if (isset($bobot[$b['sub_kategori']])) {
        $bobot[$b['sub_kategori']] = ((float)$b['bobot']) / 100;
    }
}

$aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC")->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua kombinasi siswa & mapel di TA 10 yang belum ada di nilai_rapor tapi ada di formatif / sumatif
$sql = "
    SELECT s.id as siswa_id, ar.rombel_id, m.id as mapel_id, m.nama_mapel
    FROM siswa s
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = $ta_id
    JOIN rombel r ON r.id = ar.rombel_id
    CROSS JOIN mata_pelajaran m
    WHERE m.status = 'Aktif'
      AND (
          EXISTS (SELECT 1 FROM nilai_formatif nf WHERE nf.siswa_id = s.id AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = $ta_id AND nf.kategori = '$kategori')
          OR
          EXISTS (SELECT 1 FROM nilai_sumatif ns WHERE ns.siswa_id = s.id AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = $ta_id AND ns.kategori = '$kategori')
      )
      AND NOT EXISTS (
          SELECT 1 FROM nilai_rapor nr WHERE nr.siswa_id = s.id AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = $ta_id AND nr.kategori = '$kategori'
      )
";

$missingList = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$totalSynced = 0;
foreach ($missingList as $item) {
    $siswa_id = $item['siswa_id'];
    $rombel_id = $item['rombel_id'];
    $mapel_id = $item['mapel_id'];

    // Ambil formatif
    $f_stmt = $pdo->prepare("SELECT jenis_penilaian, nilai_angka, pertemuan FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ?");
    $f_stmt->execute([$siswa_id, $mapel_id, $ta_id, $kategori]);
    $formatifs = $f_stmt->fetchAll(PDO::FETCH_ASSOC);

    $sum_nh = 0; $sum_uh = 0;
    $max_nh = 0; $max_uh = 0;
    foreach ($formatifs as $f) {
        $j = strtoupper($f['jenis_penilaian']);
        $v = (float)$f['nilai_angka'];
        $p = (int)$f['pertemuan'];
        if (strpos($j, 'UH') !== false || strpos($j, 'ULANGAN') !== false) {
            $sum_uh += $v;
            if ($p > $max_uh) $max_uh = $p;
        } else {
            $sum_nh += $v;
            if ($p > $max_nh) $max_nh = $p;
        }
    }

    // Ambil sumatif STS
    $s_stmt = $pdo->prepare("SELECT nilai FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ? AND jenis_sumatif = 'pts'");
    $s_stmt->execute([$siswa_id, $mapel_id, $ta_id, $kategori]);
    $sts = (float)$s_stmt->fetchColumn();

    $p_nh = $max_nh > 0 ? $max_nh : 1;
    $p_uh = $max_uh > 0 ? $max_uh : 1;

    $avg_nh = $sum_nh / $p_nh;
    $avg_uh = $sum_uh / $p_uh;
    $avg_sts = $sts;

    // Kalkulasi
    $kalkulasi = ($avg_nh * $bobot['nh']) + ($avg_uh * $bobot['uh']) + ($avg_sts * $bobot['sts']);
    $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
    $rata_sumatif = round($avg_sts, 1);
    $nilai_akhir = number_format($kalkulasi, 1, '.', '');

    $predikat = 'Kurang';
    foreach ($aturanPredikat as $ap) {
        if (floor($nilai_akhir) >= $ap['nilai_min'] && floor($nilai_akhir) <= $ap['nilai_max']) {
            $predikat = $ap['deskripsi_predikat'];
            break;
        }
    }

    $insert = $pdo->prepare("
        INSERT INTO nilai_rapor (siswa_id, rombel_id, tahun_ajaran_id, mapel_id, kategori, rata_formatif, rata_sumatif, nilai_akhir, predikat, deskripsi, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '', NOW(), NOW())
    ");
    $insert->execute([$siswa_id, $rombel_id, $ta_id, $mapel_id, $kategori, $rata_formatif, $rata_sumatif, $nilai_akhir, $predikat]);
    $totalSynced++;
}

echo "SUKSES SINKRONISASI MASSAL: Berhasil memasukkan $totalSynced baris ke nilai_rapor.\n";

