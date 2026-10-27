<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

function testDeskripsi($pdo, $siswa_id, $mapel_id, $nilai_akhir) {
    $tingkat = 8;
    $semester = 'Ganjil';
    $nama_siswa = "Adabi Limansyah Sagala";

    $stmt = $pdo->prepare("SELECT * FROM master_lm WHERE mapel_id = ? AND tingkat = ? AND semester = ? AND (kategori IN ('Tengah', 'STS', 'PTS') OR kategori IS NULL OR kategori = '') ORDER BY id ASC");
    $stmt->execute([$mapel_id, $tingkat, $semester]);
    $allLm = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $lmByNum = [];
    foreach ($allLm as $lm) {
        $num = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
        if ($num > 0) $lmByNum[$num] = $lm['deskripsi_lm'];
    }

    // Ambil nilai per LM dari formatif
    $stmt = $pdo->prepare("SELECT pertemuan, AVG(nilai_angka) as avg_score FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester' GROUP BY pertemuan");
    $stmt->execute([$siswa_id, $mapel_id]);
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $maxScore = -1; $minScore = 999;
    $maxPert = null; $minPert = null;
    foreach ($scores as $s) {
        $pert = (int)$s['pertemuan'];
        $val = (float)$s['avg_score'];
        if ($val > $maxScore) { $maxScore = $val; $maxPert = $pert; }
        if ($val < $minScore) { $minScore = $val; $minPert = $pert; }
    }

    $descTertinggi = '';
    $descTerendah = '';
    if ($maxPert && isset($lmByNum[$maxPert])) {
        $materi = trim(lcfirst($lmByNum[$maxPert]));
        $descTertinggi = "$nama_siswa menunjukkan penguasaan yang baik dalam $materi.";
    }
    if ($minPert && isset($lmByNum[$minPert]) && $minScore < 75) {
        $materi = trim(lcfirst($lmByNum[$minPert]));
        $descTerendah = "Perlu pendampingan lebih lanjut dalam $materi.";
    }

    return trim($descTertinggi . ' ' . $descTerendah);
}

echo "Deskripsi PAI:\n" . testDeskripsi($pdo, 33, 1, 58.5) . PHP_EOL;
echo "Deskripsi B. Indonesia:\n" . testDeskripsi($pdo, 33, 3, 37.1) . PHP_EOL;
