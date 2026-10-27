<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$filesToImport = [
    'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Safir.xlsx',
    'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Topaz fix.xlsx',
    'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Kyanite.xlsx',
    'docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Topaz.xlsx'
];

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

$overallStats = [];

foreach ($filesToImport as $filePath) {
    $baseName = basename($filePath);
    echo "=========================================================\n";
    echo "MEMPROSES TEMPLATE: $baseName\n";
    echo "=========================================================\n";
    
    $reader = IOFactory::createReaderForFile($filePath);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestDataRow();
    $highestCol = $sheet->getHighestDataColumn();
    $highestColIdx = Coordinate::columnIndexFromString($highestCol);
    
    $ta_id     = (int)$sheet->getCell('A7')->getValue();
    $rombel_id = (int)$sheet->getCell('B7')->getValue();
    $mapel_id  = (int)$sheet->getCell('C7')->getValue();
    
    if (!$ta_id || !$rombel_id || !$mapel_id) {
        echo "Metadata baris 7 tidak lengkap di $baseName (TA:$ta_id, Rombel:$rombel_id, Mapel:$mapel_id)\n";
        continue;
    }
    
    // Cari guru_id dari guru_mapel
    $gm = $pdo->query("SELECT guru_id FROM raporsmpit.guru_mapel WHERE mapel_id = $mapel_id AND rombel_id = $rombel_id AND tahun_ajaran_id = $ta_id LIMIT 1")->fetch();
    $guru_id = $gm ? $gm['guru_id'] : 1;
    
    // Ambil info nama rombel & mapel
    $rInfo = $pdo->query("SELECT nama_rombel, tingkat FROM raporsmpit.rombel WHERE id = $rombel_id")->fetch();
    $mInfo = $pdo->query("SELECT nama_mapel FROM raporsmpit.mata_pelajaran WHERE id = $mapel_id")->fetch();
    echo "Target: {$rInfo['nama_rombel']} (ID $rombel_id) | Mapel: {$mInfo['nama_mapel']} (ID $mapel_id) | Guru ID: $guru_id\n";
    
    // Kolom mapping dari baris 7
    $colMap = [];
    for ($c = 4; $c <= $highestColIdx; $c++) {
        $cLtr = Coordinate::stringFromColumnIndex($c);
        $val7 = trim((string)$sheet->getCell($cLtr . '7')->getValue());
        if ($val7 !== '') {
            $colMap[$cLtr] = $val7;
        }
    }
    echo "Kolom nilai terpetakan: " . json_encode($colMap) . "\n";
    
    // Cache LM
    $tingkatNum = 8;
    if (in_array(strtoupper($rInfo['tingkat']), ['7', 'VII'])) $tingkatNum = 7;
    elseif (in_array(strtoupper($rInfo['tingkat']), ['9', 'IX'])) $tingkatNum = 9;
    
    $lmCache = [];
    $lmRows = $pdo->query("SELECT * FROM raporsmpit.master_lm WHERE mapel_id = $mapel_id AND tingkat = $tingkatNum AND semester = 'Ganjil' AND kategori = 'Tengah'")->fetchAll();
    foreach ($lmRows as $lm) {
        $num = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
        $lmCache[$num] = $lm;
    }
    
    // Siswa di rombel
    $studentsInRombel = $pdo->query("
        SELECT s.id, s.nis, s.nama_lengkap 
        FROM raporsmpit.anggota_rombel ar
        JOIN raporsmpit.siswa s ON ar.siswa_id = s.id
        WHERE ar.rombel_id = $rombel_id
    ")->fetchAll();
    
    $stats = [
        'matched' => 0,
        'unmatched' => 0,
        'formatif_saved' => 0,
        'sumatif_saved' => 0
    ];
    
    for ($r = 10; $r <= $highestRow; $r++) {
        $nis = trim((string)$sheet->getCell("B$r")->getValue());
        $namaExcel = trim((string)$sheet->getCell("C$r")->getValue());
        
        if ($nis === '' && $namaExcel === '') continue;
        
        $matched = null;
        // 1. Cocokkan NIS
        if ($nis !== '') {
            foreach ($studentsInRombel as $st) {
                if (trim($st['nis']) === $nis) {
                    $matched = $st;
                    break;
                }
            }
        }
        // 2. Cocokkan Nama
        if (!$matched && $namaExcel !== '') {
            foreach ($studentsInRombel as $st) {
                if (strcasecmp(trim($st['nama_lengkap']), $namaExcel) === 0) {
                    $matched = $st;
                    break;
                }
            }
        }
        
        if (!$matched) {
            echo "  [GAGAL] Baris $r: NIS '$nis', Nama '$namaExcel'\n";
            $stats['unmatched']++;
            continue;
        }
        
        $siswaId = $matched['id'];
        $stats['matched']++;
        
        foreach ($colMap as $cLtr => $kode) {
            $val = $sheet->getCell($cLtr . $r)->getCalculatedValue();
            if ($val === null || trim((string)$val) === '' || !is_numeric($val)) continue;
            $val = (float)$val;
            
            if (strpos($kode, 'NH_') === 0) {
                $pert = (int)str_replace('NH_', '', $kode);
                $lmObj = $lmCache[$pert] ?? null;
                list($pred, $catatan) = getPredikatCatatan($val, $lmObj);
                
                $checkF = $pdo->prepare("SELECT id FROM raporsmpit.nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = 'Ganjil' AND kategori = 'Tengah Semester' AND jenis_penilaian = 'Tugas' AND pertemuan = ?");
                $checkF->execute([$siswaId, $mapel_id, $ta_id, $pert]);
                $ex = $checkF->fetch();
                if ($ex) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_formatif SET nilai_angka = ?, predikat = ?, catatan = ?, guru_id = ?, rombel_id = ? WHERE id = ?")->execute([$val, $pred, $catatan, $guru_id, $rombel_id, $ex['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, tanggal_penilaian, nilai_angka, predikat, catatan, status_simpan) VALUES (?, ?, ?, ?, ?, 'Ganjil', 'Tengah Semester', 'Tugas', ?, ?, ?, ?, ?, 'draft')")->execute([$siswaId, $guru_id, $mapel_id, $rombel_id, $ta_id, $pert, date('Y-m-d'), $val, $pred, $catatan]);
                }
                $stats['formatif_saved']++;
            } elseif (strpos($kode, 'UH_') === 0) {
                $pert = (int)str_replace('UH_', '', $kode);
                $lmObj = $lmCache[$pert] ?? null;
                list($pred, $catatan) = getPredikatCatatan($val, $lmObj);
                
                $checkF = $pdo->prepare("SELECT id FROM raporsmpit.nilai_formatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = 'Ganjil' AND kategori = 'Tengah Semester' AND jenis_penilaian = 'Ulangan' AND pertemuan = ?");
                $checkF->execute([$siswaId, $mapel_id, $ta_id, $pert]);
                $ex = $checkF->fetch();
                if ($ex) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_formatif SET nilai_angka = ?, predikat = ?, catatan = ?, guru_id = ?, rombel_id = ? WHERE id = ?")->execute([$val, $pred, $catatan, $guru_id, $rombel_id, $ex['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_formatif (siswa_id, guru_id, mapel_id, rombel_id, tahun_ajaran_id, semester, kategori, jenis_penilaian, pertemuan, tanggal_penilaian, nilai_angka, predikat, catatan, status_simpan) VALUES (?, ?, ?, ?, ?, 'Ganjil', 'Tengah Semester', 'Ulangan', ?, ?, ?, ?, ?, 'draft')")->execute([$siswaId, $guru_id, $mapel_id, $rombel_id, $ta_id, $pert, date('Y-m-d'), $val, $pred, $catatan]);
                }
                $stats['formatif_saved']++;
            } elseif ($kode === 'pts') {
                $checkS = $pdo->prepare("SELECT id FROM raporsmpit.nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = 'Tengah Semester' AND jenis_sumatif = 'pts'");
                $checkS->execute([$siswaId, $mapel_id, $ta_id]);
                $exS = $checkS->fetch();
                if ($exS) {
                    $pdo->prepare("UPDATE raporsmpit.nilai_sumatif SET nilai = ? WHERE id = ?")->execute([$val, $exS['id']]);
                } else {
                    $pdo->prepare("INSERT INTO raporsmpit.nilai_sumatif (siswa_id, mapel_id, tahun_ajaran_id, kategori, jenis_sumatif, nilai, status) VALUES (?, ?, ?, 'Tengah Semester', 'pts', ?, 'draft')")->execute([$siswaId, $mapel_id, $ta_id, $val]);
                }
                $stats['sumatif_saved']++;
            }
        }
    }
    
    echo "Hasil: Siswa Cocok: {$stats['matched']}, Gagal: {$stats['unmatched']} | Formatif Disimpan: {$stats['formatif_saved']} | Sumatif Disimpan: {$stats['sumatif_saved']}\n\n";
    $overallStats[$baseName] = $stats;
}

echo "=========================================================\n";
echo "REKAPITULASI KESELURUHAN TEMPLATE KOLEKTIF:\n";
echo "=========================================================\n";
print_r($overallStats);

