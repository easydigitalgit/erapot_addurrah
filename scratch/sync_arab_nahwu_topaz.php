<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ([10 => 'Bahasa Arab', 44 => 'Nahwu'] as $mapel_id => $nama_mapel) {
    // Ambil siswa Topaz yang punya formatif/sumatif
    $stmt = $pdo->prepare("
        SELECT DISTINCT s.id as siswa_id 
        FROM siswa s 
        JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 
        WHERE ar.rombel_id = 73 
          AND (
              EXISTS (SELECT 1 FROM nilai_formatif nf WHERE nf.siswa_id = s.id AND nf.mapel_id = ? AND nf.tahun_ajaran_id = 10 AND nf.kategori = 'Tengah Semester')
              OR
              EXISTS (SELECT 1 FROM nilai_sumatif ns WHERE ns.siswa_id = s.id AND ns.mapel_id = ? AND ns.tahun_ajaran_id = 10 AND ns.kategori = 'Tengah Semester')
          )
    ");
    $stmt->execute([$mapel_id, $mapel_id]);
    $siswas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $count = 0;
    foreach ($siswas as $siswa_id) {
        // Ambil formatif
        $f_stmt = $pdo->prepare("SELECT jenis_penilaian, nilai_angka, pertemuan FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester'");
        $f_stmt->execute([$siswa_id, $mapel_id]);
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

        $s_stmt = $pdo->prepare("SELECT nilai FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester' AND jenis_sumatif = 'pts'");
        $s_stmt->execute([$siswa_id, $mapel_id]);
        $sts = (float)$s_stmt->fetchColumn();

        $p_nh = $max_nh > 0 ? $max_nh : 1;
        $p_uh = $max_uh > 0 ? $max_uh : 1;
        $avg_nh = $sum_nh / $p_nh;
        $avg_uh = $sum_uh / $p_uh;
        $avg_sts = $sts;

        $kalkulasi = ($avg_nh * 0.35) + ($avg_uh * 0.35) + ($avg_sts * 0.30);
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

        $checkR = $pdo->prepare("SELECT id FROM nilai_rapor WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester'");
        $checkR->execute([$siswa_id, $mapel_id]);
        $existR = $checkR->fetchColumn();
        if ($existR) {
            $pdo->prepare("UPDATE nilai_rapor SET rata_formatif = ?, rata_sumatif = ?, nilai_akhir = ?, predikat = ?, updated_at = NOW() WHERE id = ?")
                ->execute([$rata_formatif, $rata_sumatif, $nilai_akhir, $predikat, $existR]);
        } else {
            $pdo->prepare("INSERT INTO nilai_rapor (siswa_id, rombel_id, tahun_ajaran_id, mapel_id, kategori, rata_formatif, rata_sumatif, nilai_akhir, predikat, deskripsi, created_at, updated_at) VALUES (?, 73, 10, ?, 'Tengah Semester', ?, ?, ?, ?, '', NOW(), NOW())")
                ->execute([$siswa_id, $mapel_id, $rata_formatif, $rata_sumatif, $nilai_akhir, $predikat]);
        }
        $count++;
    }
    echo "Selesai sinkronisasi $nama_mapel untuk $count siswa Topaz ke nilai_rapor.\n";
}

