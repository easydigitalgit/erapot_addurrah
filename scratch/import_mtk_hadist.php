<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$ta_id = 10;
$semester = 'Ganjil';
$kategori_formatif = 'Tengah Semester';
$kategori_sumatif = 'Tengah Semester';

function getPredikatCatatan($nilai, $lmData) {
    $angka = (int)$nilai;
    if ($angka >= 90) $pred = 'A';
    elseif ($angka >= 80) $pred = 'B';
    elseif ($angka >= 75) $pred = 'C';
    else $pred = 'D';

    $catatan = '';
    if ($lmData) {
        $k = 'deskripsi_' . strtolower($pred);
        if (!empty($lmData[$k])) {
            $catatan = $lmData[$k];
        } elseif (!empty($lmData['deskripsi_lm'])) {
            $materi = $lmData['deskripsi_lm'];
            if ($pred === 'A') $catatan = "Sangat baik memahami $materi";
            elseif ($pred === 'B') $catatan = "Baik memahami $materi";
            elseif ($pred === 'C') $catatan = "Cukup memahami $materi";
            else $catatan = "Perlu bimbingan memahami $materi";
        }
    }
    return [$pred, $catatan];
}

$aliasMap = [
    'AZURA FAIRUUZ BARDANI' => 70,
    'NAURA ZAKIYA SAKHI' => 77,
    'SYAQILLA AULIANI' => 86,
    'Muhammad daffa yusnan' => 20,
    'ATHALLAH AL SYUTHAN N' => 39,
    'FAIZ PINTARA ALHAFIZ' => 423,
    'M HABIBSYAH SYUKUR' => 429,
    'MUHAMMAD AFKAR AYUB' => 427,
    'MUHAMMAD HAFIZ FADLI' => 426,
    'RADITYA NAUFAL PUTRA' => 56,
];

$tasks = [
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN Sumatif Tengah Semester 26-27 GANJIL.xlsx',
        'mapel_id' => 4, // Matematika
        'guru_id' => 8,  // Riska Handayani
        'sheets' => [
            'GRANIT' => 72,
            'KYANITE' => 75,
            'INTAN' => 74
        ]
    ],
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN Sumatif Tengah Semester.xlsx',
        'mapel_id' => 11, // Hadist
        'guru_id' => 51,  // Luthfia Chairunnisa
        'sheets' => [
            '8 INTAN' => 74,
            '8 KYANITE' => 75,
            '8 SAFIR' => 76
        ]
    ]
];

$overallStats = [];

foreach ($tasks as $task) {
    $filePath = $task['file'];
    $mapelId = $task['mapel_id'];
    $guruId = $task['guru_id'];
    $mInfo = $pdo->query("SELECT nama_mapel FROM raporsmpit.mata_pelajaran WHERE id = $mapelId")->fetch();
    
    echo "=========================================================\n";
    echo "MEMPROSES: {$mInfo['nama_mapel']} ($filePath)\n";
    echo "=========================================================\n";
    
    // Cache LM
    $lmCache = [];
    $lmRows = $pdo->query("SELECT * FROM raporsmpit.master_lm WHERE mapel_id = $mapelId AND tingkat = 8 AND semester = 'Ganjil' AND kategori = 'Tengah'")->fetchAll();
    foreach ($lmRows as $lm) {
        $num = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
        $lmCache[$num] = $lm;
    }
    
    $reader = IOFactory::createReaderForFile($filePath);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($filePath);
    
    $stats = ['matched' => 0, 'unmatched' => 0, 'formatif_saved' => 0, 'sumatif_saved' => 0];
    
    foreach ($task['sheets'] as $sheetName => $rombelId) {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        if (!$sheet) {
            echo "Sheet $sheetName tidak ditemukan!\n";
            continue;
        }
        $highestRow = $sheet->getHighestDataRow();
        echo "--> Sheet: $sheetName (Rombel ID $rombelId, Rows: $highestRow)\n";
        
        $studentsInRombel = $pdo->query("
            SELECT s.id, s.nis, s.nama_lengkap 
            FROM raporsmpit.anggota_rombel ar
            JOIN raporsmpit.siswa s ON ar.siswa_id = s.id
            WHERE ar.rombel_id = $rombelId
        ")->fetchAll();
        
        for ($r = 10; $r <= $highestRow; $r++) {
            $namaExcel = trim((string)$sheet->getCell("B$r")->getValue());
            if ($namaExcel === '' || stripos($namaExcel, 'RATA-RATA') !== false || stripos($namaExcel, 'JUMLAH') !== false) {
                continue;
            }
            
            $matchedStudent = null;
            if (isset($aliasMap[$namaExcel])) {
                $aliasId = $aliasMap[$namaExcel];
                foreach ($studentsInRombel as $st) {
                    if ($st['id'] == $aliasId) {
                        $matchedStudent = $st;
                        break;
                    }
                }
            }
            
            if (!$matchedStudent) {
                foreach ($studentsInRombel as $st) {
                    if (strcasecmp(trim($st['nama_lengkap']), $namaExcel) === 0) {
                        $matchedStudent = $st;
                        break;
                    }
                    $cleanA = preg_replace('/[^a-zA-Z]/', '', strtolower($st['nama_lengkap']));
                    $cleanB = preg_replace('/[^a-zA-Z]/', '', strtolower($namaExcel));
                    if ($cleanA === $cleanB || strpos($cleanA, $cleanB) !== false || strpos($cleanB, $cleanA) !== false) {
                        $matchedStudent = $st;
                        break;
                    }
                }
            }
            
            if (!$matchedStudent) {
                echo "    [GAGAL] Baris $r: '$namaExcel'\n";
                $stats['unmatched']++;
                continue;
            }
            
            $siswaId = $matchedStudent['id'];
            $stats['matched']++;
            
            $nh1 = $sheet->getCell("C$r")->getCalculatedValue();
            $nh2 = $sheet->getCell("D$r")->getCalculatedValue();
            $uh1 = $sheet->getCell("H$r")->getCalculatedValue();
            $uh2 = $sheet->getCell("I$r")->getCalculatedValue();
            $sts = $sheet->getCell("M$r")->getCalculatedValue();
            
            $formatifItems = [
                ['jenis' => 'Tugas',   'pert' => 1, 'val' => $nh1, 'lm_num' => 1],
                ['jenis' => 'Tugas',   'pert' => 2, 'val' => $nh2, 'lm_num' => 2],
                ['jenis' => 'Ulangan', 'pert' => 1, 'val' => $uh1, 'lm_num' => 1],
                ['jenis' => 'Ulangan', 'pert' => 2, 'val' => $uh2, 'lm_num' => 2],
            ];
            
            foreach ($formatifItems as $fi) {
                if ($fi['val'] === null || $fi['val'] === '' || !is_numeric($fi['val'])) continue;
                $val = (float)$fi['val'];
                $lmObj = $lmCache[$fi['lm_num']] ?? null;
                list($pred, $catatan) = getPredikatCatatan($val, $lmObj);
                
                $checkF = $pdo->prepare("SELECT id FROM raporsmpit.nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = 'Ganjil' AND kategori = 'Tengah Semester' AND jenis_penilaian = ? AND pertemuan = ?");
                $checkF->execute([$siswaId, $mapelId, $ta_id, $fi['jenis'], $fi['pert']]);
                $ex = $checkF->fetch();
                if ($ex) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_formatif SET nilai_angka = ?, predikat = ?, catatan = ?, guru_id = ?, rombel_id = ? WHERE id = ?")->execute([$val, $pred, $catatan, $guruId, $rombelId, $ex['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, tanggal_penilaian, nilai_angka, predikat, catatan, status_simpan) VALUES (?, ?, ?, ?, ?, 'Ganjil', 'Tengah Semester', ?, ?, ?, ?, ?, ?, 'draft')")->execute([$siswaId, $guruId, $mapelId, $rombelId, $ta_id, $fi['jenis'], $fi['pert'], date('Y-m-d'), $val, $pred, $catatan]);
                }
                $stats['formatif_saved']++;
            }
            
            if ($sts !== null && $sts !== '' && is_numeric($sts)) {
                $valSts = (float)$sts;
                $checkS = $pdo->prepare("SELECT id FROM raporsmpit.nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = 'Tengah Semester' AND jenis_sumatif = 'pts'");
                $checkS->execute([$siswaId, $mapelId, $ta_id]);
                $exS = $checkS->fetch();
                if ($exS) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_sumatif SET nilai = ? WHERE id = ?")->execute([$valSts, $exS['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_sumatif (siswa_id, mapel_id, tahun_ajaran_id, kategori, jenis_sumatif, nilai, status) VALUES (?, ?, ?, 'Tengah Semester', 'pts', ?, 'draft')")->execute([$siswaId, $mapelId, $ta_id, $valSts]);
                }
                $stats['sumatif_saved']++;
            }
        }
    }
    
    echo "Hasil {$mInfo['nama_mapel']}: Siswa Cocok: {$stats['matched']}, Gagal: {$stats['unmatched']} | Formatif: {$stats['formatif_saved']} | Sumatif: {$stats['sumatif_saved']}\n\n";
    $overallStats[$mInfo['nama_mapel']] = $stats;
}

echo "=========================================================\n";
echo "REKAPITULASI MATEMATIKA & HADIST:\n";
echo "=========================================================\n";
print_r($overallStats);

