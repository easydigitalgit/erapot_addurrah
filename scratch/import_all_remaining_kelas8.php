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

// Cache LM untuk semua mapel kelas 8 Ganjil Tengah
$lmAllCache = [];
$lmRows = $pdo->query("SELECT * FROM raporsmpit.master_lm WHERE tingkat = 8 AND semester = 'Ganjil' AND kategori = 'Tengah'")->fetchAll();
foreach ($lmRows as $lm) {
    $num = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
    $lmAllCache[$lm['mapel_id']][$num] = $lm;
}

$batchJobs = [
    // 1. Bahasa Indonesia (8 Topaz)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Topaz).xlsx',
        'mapel_id' => 3,
        'guru_id' => 21,
        'sheets' => ['Sheet1' => 73],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'F', 'uh2' => 'G', 'sts' => 'I']
    ],
    // 2. Bahasa Indonesia (8 Granit, 8 Intan, 8 Kyanite, 8 Safir)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Granit, 8 Intan, 8 Kyanite, 8 Safir).xlsx',
        'mapel_id' => 3,
        'guru_id' => 6,
        'sheets' => ['Granit' => 72, 'Intan' => 74, 'Kyanite' => 75, 'Safir' => 76],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'H', 'uh2' => 'I', 'sts' => 'M']
    ],
    // 3. PAIBP (8 Granit)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Granit).xlsx',
        'mapel_id' => 1,
        'guru_id' => 111,
        'sheets' => ['Sheet1' => 72],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'E', 'uh2' => 'F', 'sts' => 'G']
    ],
    // 4. PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx',
        'mapel_id' => 1,
        'guru_id' => 33,
        'sheets' => ['8 TOPAZ' => 73, '8 INTAN' => 74, '8 KYANITE' => 75, '8 SAFIR' => 76],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'H', 'uh2' => 'I', 'sts' => 'M']
    ],
    // 5. Matematika (8 Topaz, 8 Safir)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Matematika (8 Topaz, 8 Safir).xlsx',
        'mapel_id' => 4,
        'guru_id' => 24,
        'sheets' => ['Safir' => 76, 'Topaz' => 73],
        'cols' => ['nh1' => 'D', 'nh2' => 'E', 'uh1' => 'F', 'uh2' => 'G', 'sts' => 'H']
    ],
    // 6. PKN (Seluruh Kelas 8)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PKN (Seluruh Kelas 8).xlsx',
        'mapel_id' => 2,
        'guru_id' => 10,
        'sheets' => ['Granit' => 72, 'Topaz' => 73, 'Intan' => 74, 'Kyanite' => 75, 'Safir' => 76],
        'cols' => ['nh1' => 'D', 'nh2' => null, 'uh1' => 'E', 'uh2' => null, 'sts' => 'F']
    ],
    // 7. IPS (Seluruh Kelas 8)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/IPS (Seluruh Kelas 8).xlsx',
        'mapel_id' => 14,
        'guru_id' => 9,
        'sheets' => ['SAFIR' => 76, 'KYANITE' => 75, 'INTAN' => 74, 'TOPAZ' => 73, 'GRANIT' => 72],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'H', 'uh2' => 'I', 'sts' => 'M']
    ],
    // 8. PJOK (8 Granit & 8 Topaz)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PJOK (8 Granit & 8 Topaz).xlsx',
        'mapel_id' => 9,
        'guru_id' => 144,
        'sheets' => ['Granit' => 72, 'Topaz' => 73],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'H', 'uh2' => 'I', 'sts' => 'M']
    ],
    // 9. PJOK (8 Intan, 8 Kyanite, 8 Safir)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PJOK (8 Intan, 8 Kyanite, 8 Safir).xlsx',
        'mapel_id' => 9,
        'guru_id' => 31,
        'sheets' => ['Intan' => 74, 'Kyanite' => 75, 'Safir' => 76],
        'cols' => ['nh1' => 'C', 'nh2' => 'D', 'uh1' => 'H', 'uh2' => 'I', 'sts' => 'M']
    ],
    // 10. Seni Budaya (8 Granit, 8 Kyanite, 8 Safir)
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Seni Budaya (8 Granit, 8 Kyanite, 8 Safir).xlsx',
        'mapel_id' => 8,
        'guru_id' => 14,
        'sheets' => ['Granit' => 72, 'Kyanite' => 75, 'Safir' => 76],
        'cols' => ['nh1' => 'D', 'nh2' => 'E', 'uh1' => 'F', 'uh2' => 'G', 'sts' => 'H']
    ],
    // 11. Informatika (Seluruh Kelas 8) - Khusus Granit
    [
        'file' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Informatika (Seluruh Kelas 8).xlsx',
        'mapel_id' => 7,
        'guru_id' => 2,
        'sheets' => ['Granit' => 72],
        'cols' => ['nh1' => 'D', 'nh2' => 'F', 'uh1' => 'E', 'uh2' => 'G', 'sts' => 'H']
    ]
];

$finalStats = [];

foreach ($batchJobs as $job) {
    $fPath = $job['file'];
    $mapelId = $job['mapel_id'];
    $guruId = $job['guru_id'];
    $cols = $job['cols'];
    $bName = basename($fPath);
    
    echo "=========================================================\n";
    echo "IMPORT BATCH: $bName (Mapel ID $mapelId)\n";
    echo "=========================================================\n";
    
    $reader = IOFactory::createReaderForFile($fPath);
    $reader->setReadDataOnly(true);
    $ss = $reader->load($fPath);
    
    $jobStats = ['matched' => 0, 'unmatched' => 0, 'formatif' => 0, 'sumatif' => 0];
    
    foreach ($job['sheets'] as $sheetName => $rombelId) {
        $sheet = $ss->getSheetByName($sheetName);
        if (!$sheet) {
            echo "  Sheet '$sheetName' tidak ditemukan di $bName\n";
            continue;
        }
        $maxRow = $sheet->getHighestDataRow();
        
        $studentsInRombel = $pdo->query("
            SELECT s.id, s.nis, s.nama_lengkap 
            FROM raporsmpit.anggota_rombel ar
            JOIN raporsmpit.siswa s ON ar.siswa_id = s.id
            WHERE ar.rombel_id = $rombelId
        ")->fetchAll();
        
        $sheetMatched = 0;
        
        for ($r = 10; $r <= $maxRow; $r++) {
            // Kolom nama bisa di B atau C tergantung file
            $valB = trim((string)$sheet->getCell("B$r")->getValue());
            $valC = trim((string)$sheet->getCell("C$r")->getValue());
            
            $namaExcel = '';
            $nisExcel = '';
            if (is_numeric(str_replace(['.', ' ', '-'], '', $valB)) && !empty($valC)) {
                $nisExcel = $valB;
                $namaExcel = $valC;
            } else {
                $namaExcel = $valB;
                $nisExcel = '';
            }
            
            if ($namaExcel === '' || stripos($namaExcel, 'RATA') !== false || stripos($namaExcel, 'JUMLAH') !== false) {
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
            
            if (!$matchedStudent && !empty($nisExcel)) {
                foreach ($studentsInRombel as $st) {
                    if (trim($st['nis']) === $nisExcel) {
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
                echo "    [GAGAL] Baris $r di $sheetName: '$namaExcel' (NIS: $nisExcel)\n";
                $jobStats['unmatched']++;
                continue;
            }
            
            $siswaId = $matchedStudent['id'];
            $sheetMatched++;
            $jobStats['matched']++;
            
            // Format Nilai Formatif
            $nh1Val = $cols['nh1'] ? $sheet->getCell($cols['nh1'] . $r)->getCalculatedValue() : null;
            $nh2Val = $cols['nh2'] ? $sheet->getCell($cols['nh2'] . $r)->getCalculatedValue() : null;
            $uh1Val = $cols['uh1'] ? $sheet->getCell($cols['uh1'] . $r)->getCalculatedValue() : null;
            $uh2Val = $cols['uh2'] ? $sheet->getCell($cols['uh2'] . $r)->getCalculatedValue() : null;
            $stsVal = $cols['sts'] ? $sheet->getCell($cols['sts'] . $r)->getCalculatedValue() : null;
            
            $items = [
                ['jenis' => 'Tugas',   'pert' => 1, 'val' => $nh1Val, 'lm' => 1],
                ['jenis' => 'Tugas',   'pert' => 2, 'val' => $nh2Val, 'lm' => 2],
                ['jenis' => 'Ulangan', 'pert' => 1, 'val' => $uh1Val, 'lm' => 1],
                ['jenis' => 'Ulangan', 'pert' => 2, 'val' => $uh2Val, 'lm' => 2],
            ];
            
            foreach ($items as $it) {
                if ($it['val'] === null || trim((string)$it['val']) === '' || !is_numeric($it['val'])) continue;
                $val = (float)$it['val'];
                $lmObj = $lmAllCache[$mapelId][$it['lm']] ?? null;
                list($pred, $catatan) = getPredikatCatatan($val, $lmObj);
                
                $checkF = $pdo->prepare("SELECT id FROM raporsmpit.nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = 'Ganjil' AND kategori = 'Tengah Semester' AND jenis_penilaian = ? AND pertemuan = ?");
                $checkF->execute([$siswaId, $mapelId, $ta_id, $it['jenis'], $it['pert']]);
                $exF = $checkF->fetch();
                if ($exF) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_formatif SET nilai_angka = ?, predikat = ?, catatan = ?, guru_id = ?, rombel_id = ? WHERE id = ?")->execute([$val, $pred, $catatan, $guruId, $rombelId, $exF['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, tanggal_penilaian, nilai_angka, predikat, catatan, status_simpan) VALUES (?, ?, ?, ?, ?, 'Ganjil', 'Tengah Semester', ?, ?, ?, ?, ?, ?, 'draft')")->execute([$siswaId, $guruId, $mapelId, $rombelId, $ta_id, $it['jenis'], $it['pert'], date('Y-m-d'), $val, $pred, $catatan]);
                }
                $jobStats['formatif']++;
            }
            
            if ($stsVal !== null && trim((string)$stsVal) !== '' && is_numeric($stsVal)) {
                $valSts = (float)$stsVal;
                $checkS = $pdo->prepare("SELECT id FROM raporsmpit.nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = 'Tengah Semester' AND jenis_sumatif = 'pts'");
                $checkS->execute([$siswaId, $mapelId, $ta_id]);
                $exS = $checkS->fetch();
                if ($exS) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_sumatif SET nilai = ? WHERE id = ?")->execute([$valSts, $exS['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_sumatif (siswa_id, mapel_id, tahun_ajaran_id, kategori, jenis_sumatif, nilai, status) VALUES (?, ?, ?, 'Tengah Semester', 'pts', ?, 'draft')")->execute([$siswaId, $mapelId, $ta_id, $valSts]);
                }
                $jobStats['sumatif']++;
            }
        }
        echo "  Sheet '$sheetName' ($rombelId): $sheetMatched siswa diproses\n";
    }
    echo "Summary $bName: Matched={$jobStats['matched']}, Unmatched={$jobStats['unmatched']} | Formatif={$jobStats['formatif']}, Sumatif={$jobStats['sumatif']}\n\n";
    $finalStats[$bName] = $jobStats;
}

echo "=========================================================\n";
echo "REKAPITULASI IMPORT KESELURUHAN:\n";
echo "=========================================================\n";
print_r($finalStats);

