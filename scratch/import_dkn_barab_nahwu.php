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
$guru_id = 4; // Septian Syahputra, A.Md. Kom

$files = [
    [
        'path' => 'docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx',
        'mapel_id' => 10,
        'mapel_name' => 'Bahasa Arab'
    ],
    [
        'path' => 'docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx',
        'mapel_id' => 44,
        'mapel_name' => 'Nahwu'
    ]
];

$rombelMap = [
    'INTAN' => 74,
    'SAFIR' => 76,
    'KYANITE' => 75
];

// Cache LM untuk catatan otomatis
$lmCache = [];
$lmRows = $pdo->query("SELECT * FROM raporsmpit.master_lm WHERE mapel_id IN (10, 44) AND tingkat = 8 AND semester = 'Ganjil' AND kategori = 'Tengah'")->fetchAll();
foreach ($lmRows as $lm) {
    $num = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
    $lmCache[$lm['mapel_id']][$num] = $lm;
}

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

$totalStats = [
    'formatif_inserted' => 0,
    'formatif_updated'  => 0,
    'sumatif_inserted'  => 0,
    'sumatif_updated'   => 0,
    'students_matched'  => 0,
    'students_unmatched'=> 0
];

foreach ($files as $fInfo) {
    $filePath = $fInfo['path'];
    $mapelId = $fInfo['mapel_id'];
    $mapelName = $fInfo['mapel_name'];
    
    echo "=========================================================\n";
    echo "MEMPROSES: $mapelName ($filePath)\n";
    echo "=========================================================\n";
    
    $reader = IOFactory::createReaderForFile($filePath);
    $reader->setReadDataOnly(false);
    $spreadsheet = $reader->load($filePath);
    
    foreach ($spreadsheet->getSheetNames() as $sheetName) {
        $cleanSheetName = strtoupper(trim($sheetName));
        if (!isset($rombelMap[$cleanSheetName])) {
            echo "  Sheet '$sheetName' dilewati (bukan Intan/Safir/Kyanite)\n";
            continue;
        }
        $rombelId = $rombelMap[$cleanSheetName];
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $highestRow = $sheet->getHighestDataRow();
        
        echo "\n--> Sheet $sheetName (Rombel ID $rombelId, Rows: $highestRow)\n";
        
        // Ambil daftar siswa di rombel ini
        $studentsInRombel = $pdo->query("
            SELECT s.id, s.nis, s.nama_lengkap 
            FROM raporsmpit.anggota_rombel ar
            JOIN raporsmpit.siswa s ON ar.siswa_id = s.id
            WHERE ar.rombel_id = $rombelId
        ")->fetchAll();
        
        $sheetMatched = 0;
        
        for ($r = 10; $r <= $highestRow; $r++) {
            $namaExcel = trim((string)$sheet->getCell("B$r")->getValue());
            if ($namaExcel === '' || stripos($namaExcel, 'RATA-RATA') !== false || stripos($namaExcel, 'JUMLAH') !== false) {
                continue;
            }
            
            // Pencocokan Siswa
            $matchedStudent = null;
            foreach ($studentsInRombel as $st) {
                // Cocok persis
                if (strcasecmp(trim($st['nama_lengkap']), $namaExcel) === 0) {
                    $matchedStudent = $st;
                    break;
                }
                // Kemiripan soundex/metaphone atau substring jika nama agak beda
                $cleanA = preg_replace('/[^a-zA-Z]/', '', strtolower($st['nama_lengkap']));
                $cleanB = preg_replace('/[^a-zA-Z]/', '', strtolower($namaExcel));
                if ($cleanA === $cleanB || strpos($cleanA, $cleanB) !== false || strpos($cleanB, $cleanA) !== false) {
                    $matchedStudent = $st;
                    break;
                }
            }
            
            if (!$matchedStudent) {
                echo "    [GAGAL COCOK] Baris $r: '$namaExcel'\n";
                $totalStats['students_unmatched']++;
                continue;
            }
            
            $siswaId = $matchedStudent['id'];
            $sheetMatched++;
            $totalStats['students_matched']++;
            
            // Ambil Nilai dari Excel
            $nh1 = $sheet->getCell("C$r")->getCalculatedValue();
            $nh2 = $sheet->getCell("D$r")->getCalculatedValue();
            $uh1 = $sheet->getCell("E$r")->getCalculatedValue();
            $uh2 = $sheet->getCell("F$r")->getCalculatedValue();
            $sts = $sheet->getCell("G$r")->getCalculatedValue();
            
            $formatifItems = [
                ['jenis' => 'Tugas',   'pert' => 1, 'val' => $nh1, 'lm_num' => 1],
                ['jenis' => 'Tugas',   'pert' => 2, 'val' => $nh2, 'lm_num' => 2],
                ['jenis' => 'Ulangan', 'pert' => 1, 'val' => $uh1, 'lm_num' => 1],
                ['jenis' => 'Ulangan', 'pert' => 2, 'val' => $uh2, 'lm_num' => 2],
            ];
            
            // Simpan Formatif
            foreach ($formatifItems as $fi) {
                if ($fi['val'] === null || $fi['val'] === '') continue;
                $val = (float)$fi['val'];
                $lmObj = $lmCache[$mapelId][$fi['lm_num']] ?? null;
                list($pred, $catatan) = getPredikatCatatan($val, $lmObj);
                
                // Cek apakah sudah ada
                $checkF = $pdo->prepare("
                    SELECT id FROM raporsmpit.nilai_formatif 
                    WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = ? AND kategori = ? AND jenis_penilaian = ? AND pertemuan = ?
                ");
                $checkF->execute([$siswaId, $mapelId, $ta_id, $semester, $kategori_formatif, $fi['jenis'], $fi['pert']]);
                $existF = $checkF->fetch();
                
                if ($existF) {
                    $upd = $pdo->prepare("
                        UPDATE raporsmpit.nilai_formatif 
                        SET nilai_angka = ?, predikat = ?, catatan = ?, guru_id = ?, rombel_id = ?, status_simpan = 'draft' 
                        WHERE id = ?
                    ");
                    $upd->execute([$val, $pred, $catatan, $guru_id, $rombelId, $existF['id']]);
                    $totalStats['formatif_updated']++;
                } else {
                    $ins = $pdo->prepare("
                        INSERT INTO raporsmpit.nilai_formatif 
                        (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, tanggal_penilaian, nilai_angka, predikat, catatan, status_simpan)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')
                    ");
                    $ins->execute([$siswaId, $guru_id, $mapelId, $rombelId, $ta_id, $semester, $kategori_formatif, $fi['jenis'], $fi['pert'], date('Y-m-d'), $val, $pred, $catatan]);
                    $totalStats['formatif_inserted']++;
                }
            }
            
            // Simpan Sumatif STS
            if ($sts !== null && $sts !== '') {
                $valSts = (float)$sts;
                $checkS = $pdo->prepare("
                    SELECT id FROM raporsmpit.nilai_sumatif 
                    WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ? AND jenis_sumatif = 'pts'
                ");
                $checkS->execute([$siswaId, $mapelId, $ta_id, $kategori_sumatif]);
                $existS = $checkS->fetch();
                
                if ($existS) {
                    $updS = $pdo->prepare("
                        UPDATE raporsmpit.nilai_sumatif 
                        SET nilai = ? 
                        WHERE id = ?
                    ");
                    $updS->execute([$valSts, $existS['id']]);
                    $totalStats['sumatif_updated']++;
                } else {
                    $insS = $pdo->prepare("
                        INSERT INTO raporsmpit.nilai_sumatif 
                        (siswa_id, mapel_id, tahun_ajaran_id, kategori, jenis_sumatif, nilai, status)
                        VALUES (?, ?, ?, ?, 'pts', ?, 'draft')
                    ");
                    $insS->execute([$siswaId, $mapelId, $ta_id, $kategori_sumatif, $valSts]);
                    $totalStats['sumatif_inserted']++;
                }
            }
        }
        echo "    Berhasil memproses $sheetMatched siswa di sheet $sheetName\n";
    }
}

echo "\n=========================================================\n";
echo "REKAPITULASI IMPORT BAHASA ARAB & NAHWU KELAS 8:\n";
echo "=========================================================\n";
print_r($totalStats);

