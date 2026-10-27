<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$filePath = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Topaz).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$aliasMap = [
    'ATHALLAH AL SYUTHAN N' => 39,
    'FAIZ PINTARA ALHAFIZ' => 423,
    'M HABIBSYAH SYUKUR' => 429,
    'ARSY ALFARIZI' => 37,
    'MUHAMMAD AFKAR AYUB' => 427,
    'MUHAMMAD HAFIZ FADLI' => 426,
    'RADITYA NAUFAL PUTRA' => 56,
    'AHMAD YUSRI HABIBI' => 34,
    'DIMAS ALVINO' => 41,
];

$aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC")->fetchAll(PDO::FETCH_ASSOC);

$synced = 0;
for ($r = 10; $r <= 45; $r++) {
    $namaExcel = trim($sheet->getCell('B' . $r)->getFormattedValue());
    if (empty($namaExcel)) continue;

    $nh1 = (float)$sheet->getCell('C' . $r)->getFormattedValue();
    $nh2 = (float)$sheet->getCell('D' . $r)->getFormattedValue();
    $nh3 = (float)$sheet->getCell('E' . $r)->getFormattedValue();
    $uh1 = (float)$sheet->getCell('F' . $r)->getFormattedValue();
    $uh2 = (float)$sheet->getCell('G' . $r)->getFormattedValue();
    $uh3 = (float)$sheet->getCell('H' . $r)->getFormattedValue();
    $sts = (float)$sheet->getCell('I' . $r)->getFormattedValue();
    $rata_nh = (float)$sheet->getCell('J' . $r)->getFormattedValue();
    $rata_uh = (float)$sheet->getCell('K' . $r)->getFormattedValue();
    $nr = (float)$sheet->getCell('L' . $r)->getFormattedValue();

    $siswa_id = null;
    if (isset($aliasMap[$namaExcel])) {
        $siswa_id = $aliasMap[$namaExcel];
    } else {
        $stmt = $pdo->prepare("SELECT s.id FROM siswa s JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = 10 WHERE ar.rombel_id = 73 AND s.nama_lengkap LIKE ?");
        $stmt->execute(["%$namaExcel%"]);
        $siswa_id = $stmt->fetchColumn();
    }

    if (!$siswa_id) {
        echo "Gagal menemukan siswa B. Indo: $namaExcel\n";
        continue;
    }

    // Pastikan formatif & sumatif terisi di DB
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Tugas', 1, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $nh1]);
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Tugas', 2, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $nh2]);
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Tugas', 3, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $nh3]);
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Ulangan', 1, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $uh1]);
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Ulangan', 2, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $uh2]);
    $pdo->prepare("INSERT INTO nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, nilai_angka, status_simpan, created_at, updated_at) VALUES (?, 1, 3, 73, 10, 'Ganjil', 'Tengah Semester', 'Ulangan', 3, ?, 'terkunci', NOW(), NOW()) ON DUPLICATE KEY UPDATE nilai_angka = VALUES(nilai_angka)")->execute([$siswa_id, $uh3]);

    // Sumatif STS
    $checkS = $pdo->prepare("SELECT id FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = 3 AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester'");
    $checkS->execute([$siswa_id]);
    $existS = $checkS->fetchColumn();
    if ($existS) {
        $pdo->prepare("UPDATE nilai_sumatif SET nilai = ?, updated_at = NOW() WHERE id = ?")->execute([$sts, $existS]);
    } else {
        $pdo->prepare("INSERT INTO nilai_sumatif (siswa_id, mapel_id, tahun_ajaran_id, kategori, jenis_sumatif, nilai, status, created_at, updated_at) VALUES (?, 3, 10, 'Tengah Semester', 'pts', ?, 'draft', NOW(), NOW())")->execute([$siswa_id, $sts]);
    }

    $rata_formatif = round(($rata_nh + $rata_uh) / 2, 1);
    $rata_sumatif = $sts;
    $nilai_akhir = $nr > 0 ? $nr : round(($rata_nh * 0.35) + ($rata_uh * 0.35) + ($sts * 0.30), 1);

    $predikat = 'Kurang';
    foreach ($aturanPredikat as $ap) {
        if (floor($nilai_akhir) >= $ap['nilai_min'] && floor($nilai_akhir) <= $ap['nilai_max']) {
            $predikat = $ap['deskripsi_predikat'];
            break;
        }
    }

    $checkR = $pdo->prepare("SELECT id FROM nilai_rapor WHERE siswa_id = ? AND mapel_id = 3 AND tahun_ajaran_id = 10 AND kategori = 'Tengah Semester'");
    $checkR->execute([$siswa_id]);
    $existR = $checkR->fetchColumn();
    if ($existR) {
        $pdo->prepare("UPDATE nilai_rapor SET rata_formatif = ?, rata_sumatif = ?, nilai_akhir = ?, predikat = ?, updated_at = NOW() WHERE id = ?")
            ->execute([$rata_formatif, $rata_sumatif, $nilai_akhir, $predikat, $existR]);
    } else {
        $pdo->prepare("INSERT INTO nilai_rapor (siswa_id, rombel_id, tahun_ajaran_id, mapel_id, kategori, rata_formatif, rata_sumatif, nilai_akhir, predikat, deskripsi, created_at, updated_at) VALUES (?, 73, 10, 3, 'Tengah Semester', ?, ?, ?, ?, '', NOW(), NOW())")
            ->execute([$siswa_id, $rata_formatif, $rata_sumatif, $nilai_akhir, $predikat]);
    }

    $synced++;
}

echo "SUKSES! Total siswa Bahasa Indonesia Topaz masuk ke nilai_rapor: $synced dari 31 siswa.\n";

