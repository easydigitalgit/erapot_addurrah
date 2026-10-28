<?php
/**
 * ==============================================================================
 * CLI TOOL: SINKRONISASI SEMUA NILAI RAPOR (MASSAL / SEMUA KELAS)
 * Sistem: eRapor SMP IT Ad-Durrah
 * ==============================================================================
 * 
 * Skrip CLI ini mereplikasi fungsi tombol "Sinkronkan Semua Nilai" yang ada pada
 * dashboard Wali Kelas (ProgresNilaiController::syncSemuaMapel), namun dapat
 * memproses SELURUH rombel di sekolah secara massal sekaligus tanpa perlu login
 * satu per satu ke akun tiap wali kelas.
 * 
 * Penggunaan CLI:
 *   1. Sinkronkan semua kelas untuk Tengah Semester (STS):
 *      php sync_semua_nilai.php
 * 
 *   2. Sinkronkan semua kelas untuk Akhir Semester (SAS/PAS):
 *      php sync_semua_nilai.php --kategori="Akhir Semester"
 * 
 *   3. Sinkronkan kelas tertentu saja (berdasarkan ID Rombel):
 *      php sync_semua_nilai.php --rombel=77
 * 
 *   4. Sinkronkan mata pelajaran tertentu saja:
 *      php sync_semua_nilai.php --mapel=5
 * 
 *   5. Mode bantuan / dokumentasi opsi:
 *      php sync_semua_nilai.php --help
 * ==============================================================================
 */

// Pastikan skrip hanya dijalankan via CLI
if (php_sapi_name() !== 'cli') {
    die("Akses ditolak. Skrip ini hanya dapat dijalankan melalui terminal CLI.\n");
}

// ------------------------------------------------------------------------------
// 1. BACA KONFIGURASI DATABASE DARI .env
// ------------------------------------------------------------------------------
$envFile = __DIR__ . '/backend/.env';
if (!file_exists($envFile)) {
    $envFile = __DIR__ . '/.env';
}

$dbHost = 'localhost';
$dbName = 'raporsmpit';
$dbUser = 'dev';
$dbPass = '12345678';
$dbPort = 3306;

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0) continue;
        if (preg_match('/^database\.default\.hostname\s*=\s*(.+)$/i', $line, $m)) $dbHost = trim($m[1], " '\"");
        if (preg_match('/^database\.default\.database\s*=\s*(.+)$/i', $line, $m)) $dbName = trim($m[1], " '\"");
        if (preg_match('/^database\.default\.username\s*=\s*(.+)$/i', $line, $m)) $dbUser = trim($m[1], " '\"");
        if (preg_match('/^database\.default\.password\s*=\s*(.+)$/i', $line, $m)) $dbPass = trim($m[1], " '\"");
        if (preg_match('/^database\.default\.port\s*=\s*(.+)$/i', $line, $m))     $dbPort = (int)trim($m[1], " '\"");
    }
}

try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]);
} catch (PDOException $e) {
    echo "\n[ERROR] Gagal terkoneksi ke database '{$dbName}': " . $e->getMessage() . "\n";
    exit(1);
}

// ------------------------------------------------------------------------------
// 2. PARSING ARGUMEN CLI
// ------------------------------------------------------------------------------
$options = getopt('', ['kategori::', 'rombel::', 'mapel::', 'ta::', 'help']);

if (isset($options['help'])) {
    echo <<<HELP

PENGGUNAAN:
  php sync_semua_nilai.php [OPSI]

OPSI:
  --kategori=NAMA   Kategori rapor: "Tengah Semester" (default) atau "Akhir Semester"
  --rombel=ID       ID rombel spesifik (opsional, jika ingin sinkronkan 1 rombel saja)
  --mapel=ID        ID mapel spesifik (opsional, jika ingin sinkronkan 1 mapel saja)
  --ta=ID           ID tahun ajaran spesifik (opsional, default: Tahun Ajaran Aktif)
  --help            Menampilkan pesan bantuan ini

CONTOH:
  php sync_semua_nilai.php
  php sync_semua_nilai.php --kategori="Tengah Semester"
  php sync_semua_nilai.php --kategori="Akhir Semester" --rombel=72

HELP;
    exit(0);
}

$kategoriInput = $options['kategori'] ?? 'Tengah Semester';
$kategori = (stripos($kategoriInput, 'akhir') !== false) ? 'Akhir Semester' : 'Tengah Semester';
$kategoriDB = ($kategori === 'Tengah Semester') ? 'Tengah' : 'Akhir';

$filterRombelId = isset($options['rombel']) ? (int)$options['rombel'] : null;
$filterMapelId  = isset($options['mapel'])  ? (int)$options['mapel']  : null;
$customTaId     = isset($options['ta'])     ? (int)$options['ta']     : null;

// ------------------------------------------------------------------------------
// 3. AMBIL TAHUN AJARAN AKTIF
// ------------------------------------------------------------------------------
if ($customTaId) {
    $stmtTa = $pdo->prepare("SELECT * FROM tahun_ajaran WHERE id = ?");
    $stmtTa->execute([$customTaId]);
    $taAktif = $stmtTa->fetch();
} else {
    $taAktif = $pdo->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif' OR status = '1' ORDER BY id DESC LIMIT 1")->fetch();
}

if (!$taAktif) {
    echo "\n[ERROR] Tahun ajaran aktif tidak ditemukan di tabel tahun_ajaran.\n";
    exit(1);
}

$taId       = (int)$taAktif['id'];
$tahunNama  = $taAktif['tahun'];
$semester   = $taAktif['semester'];

// ------------------------------------------------------------------------------
// 4. ATURAN BOBOT DAN ATURAN PREDIKAT
// ------------------------------------------------------------------------------
$bobot = [
    'tengah_semester' => ['nh' => 35.0, 'uh' => 35.0, 'sts' => 30.0],
    'akhir_semester'  => ['nh' => 35.0, 'uh' => 35.0, 'sts' => 15.0, 'sas' => 15.0]
];
try {
    $qBobot = $pdo->query("SELECT * FROM setting_bobot_nilai")->fetchAll();
    foreach ($qBobot as $row) {
        $kat = strtolower(trim($row['kategori']));
        $sub = strtolower(trim($row['sub_kategori']));
        if (isset($bobot[$kat][$sub])) {
            $bobot[$kat][$sub] = (float)$row['bobot'];
        }
    }
} catch (Exception $e) {}

$aturanPredikat = [];
try {
    $aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai WHERE is_active = 1 ORDER BY nilai_max DESC")->fetchAll();
} catch (Exception $e) {}

// Cek kolom yang tersedia di nilai_rapor
$hasDeskripsiTertinggi = false;
$hasDeskripsiTerendah  = false;
$hasDeskripsiGeneral   = false;
try {
    $colsNr = $pdo->query("DESCRIBE nilai_rapor")->fetchAll(PDO::FETCH_COLUMN);
    $hasDeskripsiTertinggi = in_array('deskripsi_tertinggi', $colsNr);
    $hasDeskripsiTerendah  = in_array('deskripsi_terendah', $colsNr);
    $hasDeskripsiGeneral   = in_array('deskripsi', $colsNr);
} catch (Exception $e) {}

// ------------------------------------------------------------------------------
// 5. BANNER TAMPILAN CLI
// ------------------------------------------------------------------------------
echo "\n==============================================================================\n";
echo "      eRapor SMP IT Ad-Durrah -- CLI Tool Sinkronisasi Semua Nilai           \n";
echo "==============================================================================\n";
echo "  Tahun Ajaran : {$tahunNama} ({$semester}) [ID: {$taId}]\n";
echo "  Kategori     : {$kategori}\n";
if ($kategori === 'Tengah Semester') {
    echo "  Bobot STS    : NH = {$bobot['tengah_semester']['nh']}%, UH = {$bobot['tengah_semester']['uh']}%, STS = {$bobot['tengah_semester']['sts']}%\n";
} else {
    echo "  Bobot SAS    : NH = {$bobot['akhir_semester']['nh']}%, UH = {$bobot['akhir_semester']['uh']}%, STS = {$bobot['akhir_semester']['sts']}%, SAS = {$bobot['akhir_semester']['sas']}%\n";
}
if ($filterRombelId) echo "  Filter Rombel: ID {$filterRombelId}\n";
if ($filterMapelId)  echo "  Filter Mapel : ID {$filterMapelId}\n";
echo "==============================================================================\n\n";

// ------------------------------------------------------------------------------
// 6. AMBIL DAFTAR ROMBEL YANG AKAN DIPROSES
// ------------------------------------------------------------------------------
$sqlRombel = "
    SELECT r.id, r.nama_rombel, r.tingkat, r.kurikulum_id, r.wali_kelas_id,
           gt.nama_lengkap as nama_wali
    FROM rombel r
    LEFT JOIN guru_tendik gt ON gt.id = r.wali_kelas_id
    WHERE r.id_tahun_ajaran = :ta_id
";
if ($filterRombelId) {
    $sqlRombel .= " AND r.id = :rombel_id";
}
$sqlRombel .= " ORDER BY r.tingkat, r.nama_rombel";

$stmtR = $pdo->prepare($sqlRombel);
$paramsR = [':ta_id' => $taId];
if ($filterRombelId) $paramsR[':rombel_id'] = $filterRombelId;
$stmtR->execute($paramsR);
$rombelList = $stmtR->fetchAll();

if (empty($rombelList)) {
    echo "[!] Tidak ada rombel yang ditemukan untuk Tahun Ajaran aktif.\n";
    exit(0);
}

$startTimeTotal = microtime(true);
$totalSiswaDisinkron = 0;
$totalNilaiRaporDisinkron = 0;
$totalRombelSukses = 0;

// Helper function untuk pembagi dinamis
function hitungPembagiDinamis($formatifs) {
    $maxNh = 0;
    $maxUh = 0;
    foreach ($formatifs as $f) {
        $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
        $pert  = (int)($f['pertemuan'] ?? 0);
        $nilai = (float)($f['nilai_angka'] ?? 0);
        if ($nilai > 0) {
            if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
                if ($pert > $maxUh) $maxUh = $pert;
            } else {
                if ($pert > $maxNh) $maxNh = $pert;
            }
        }
    }
    return [
        'nh' => $maxNh > 0 ? $maxNh : 1,
        'uh' => $maxUh > 0 ? $maxUh : 1
    ];
}

// ------------------------------------------------------------------------------
// 7. LOOPING SINKRONISASI SETIAP ROMBEL
// ------------------------------------------------------------------------------
foreach ($rombelList as $rombelIdx => $rombel) {
    $rombelId   = (int)$rombel['id'];
    $rombelName = "{$rombel['tingkat']} {$rombel['nama_rombel']}";
    $waliName   = $rombel['nama_wali'] ?: 'Belum ditentukan';

    echo sprintf("[%2d/%2d] Memproses Kelas: %-22s (Wali: %s)...\n",
        $rombelIdx + 1, count($rombelList), $rombelName, $waliName
    );

    // Ambil siswa aktif di kelas ini (dari rombel_id langsung dan anggota_rombel)
    $stmtS = $pdo->prepare("
        SELECT DISTINCT s.id, s.nis, s.nama_lengkap
        FROM siswa s
        LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.deleted_at IS NULL AND ar.tahun_ajaran_id = :ta_id
        WHERE (s.rombel_id = :rombel_id OR ar.rombel_id = :rombel_id2)
          AND s.status_siswa = 'Aktif'
        ORDER BY s.nama_lengkap
    ");
    $stmtS->execute([':ta_id' => $taId, ':rombel_id' => $rombelId, ':rombel_id2' => $rombelId]);
    $siswaList = $stmtS->fetchAll();

    if (empty($siswaList)) {
        echo "     [SKIP] Tidak ada siswa aktif di kelas ini.\n\n";
        continue;
    }

    $siswaIds = array_column($siswaList, 'id');
    $siswaIdPlaceholders = implode(',', array_fill(0, count($siswaIds), '?'));

    // Ambil daftar mata pelajaran (Lapis 1 Kurikulum, Lapis 2 Jadwal, Lapis 3 Nilai Existing)
    $listMapel = [];
    if (!empty($rombel['kurikulum_id'])) {
        $stmtM1 = $pdo->prepare("
            SELECT id as mapel_id, nama_mapel, kkm 
            FROM mata_pelajaran 
            WHERE kurikulum_id = ? AND status = 'Aktif'
            ORDER BY id
        ");
        $stmtM1->execute([$rombel['kurikulum_id']]);
        $listMapel = $stmtM1->fetchAll();
    }

    if (empty($listMapel)) {
        $stmtM2 = $pdo->prepare("
            SELECT DISTINCT mp.id as mapel_id, mp.nama_mapel, mp.kkm
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran mp ON mp.id = jp.mapel_id
            WHERE jp.rombel_id = ?
            ORDER BY mp.id
        ");
        $stmtM2->execute([$rombelId]);
        $listMapel = $stmtM2->fetchAll();
    }

    if (empty($listMapel)) {
        $sqlM3 = "
            SELECT DISTINCT mp.id as mapel_id, mp.nama_mapel, mp.kkm
            FROM nilai_sumatif ns
            JOIN mata_pelajaran mp ON mp.id = ns.mapel_id
            WHERE ns.siswa_id IN ($siswaIdPlaceholders) AND ns.tahun_ajaran_id = ?
            ORDER BY mp.id
        ";
        $paramsM3 = array_merge($siswaIds, [$taId]);
        $stmtM3 = $pdo->prepare($sqlM3);
        $stmtM3->execute($paramsM3);
        $listMapel = $stmtM3->fetchAll();
    }

    // Filter jika hanya ingin mapel tertentu
    if ($filterMapelId) {
        $listMapel = array_values(array_filter($listMapel, fn($m) => (int)$m['mapel_id'] === $filterMapelId));
    }

    if (empty($listMapel)) {
        echo "     [SKIP] Tidak ada mata pelajaran yang ditemukan untuk rombel ini.\n\n";
        continue;
    }

    // Bersihkan tingkat rombel untuk referensi LM
    $tingkatClean = 0;
    $tingkatStr = strtoupper(trim((string)$rombel['tingkat']));
    $angka = preg_replace('/[^0-9]/', '', $tingkatStr);
    if (!empty($angka)) {
        $tingkatClean = (int)$angka;
    } else {
        $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
        if (preg_match('/\b(VII|VIII|IX|X|XI|XII)\b/', $tingkatStr, $matches)) {
            $tingkatClean = $romToNum[$matches[1]] ?? 0;
        }
    }

    $pdo->beginTransaction();
    $nilaiRaporRombel = 0;

    try {
        foreach ($listMapel as $mapel) {
            $mapelId = (int)$mapel['mapel_id'];

            // 1. Ambil Master LM untuk deskripsi capaian
            $stmtLm = $pdo->prepare("
                SELECT id, kode_lm, deskripsi_lm 
                FROM master_lm
                WHERE mapel_id = :mapel_id AND tingkat = :tingkat AND semester = :semester
                  AND (kategori = :kat1 OR kategori = :kat2 OR kategori IS NULL OR kategori = '')
                ORDER BY id ASC
            ");
            $stmtLm->execute([
                ':mapel_id' => $mapelId,
                ':tingkat'  => $tingkatClean,
                ':semester' => $semester,
                ':kat1'     => $kategoriDB,
                ':kat2'     => $kategori
            ]);
            $allLMs = $stmtLm->fetchAll();

            // 2. Ambil seluruh nilai formatif rombel & mapel ini
            $stmtF = $pdo->prepare("
                SELECT siswa_id, jenis_penilaian, pertemuan, nilai_angka
                FROM nilai_formatif
                WHERE mapel_id = :mapel_id AND tahun_ajaran_id = :ta_id AND rombel_id = :rombel_id
                  AND (deleted_at IS NULL)
                  AND (kategori = :kat1 OR kategori = :kat2 OR kategori IS NULL OR kategori = '')
            ");
            $stmtF->execute([
                ':mapel_id'  => $mapelId,
                ':ta_id'     => $taId,
                ':rombel_id' => $rombelId,
                ':kat1'      => $kategori,
                ':kat2'      => $kategoriDB
            ]);
            $allFormatif = $stmtF->fetchAll();

            // 3. Ambil seluruh nilai sumatif rombel & mapel ini
            $sqlSumatif = "
                SELECT siswa_id, jenis_sumatif, nilai
                FROM nilai_sumatif
                WHERE mapel_id = ? AND tahun_ajaran_id = ? AND (deleted_at IS NULL) AND siswa_id IN ($siswaIdPlaceholders)
            ";
            $paramsSumatif = array_merge([$mapelId, $taId], $siswaIds);
            $stmtSum = $pdo->prepare($sqlSumatif);
            $stmtSum->execute($paramsSumatif);
            $allSumatif = $stmtSum->fetchAll();

            // 4. Hitung pembagi dinamis pertemuan
            $pembagi = hitungPembagiDinamis($allFormatif);

            // Grouping nilai per siswa
            $formatifBySiswa = [];
            foreach ($allFormatif as $f) {
                $formatifBySiswa[$f['siswa_id']][] = $f;
            }
            $sumatifBySiswa = [];
            foreach ($allSumatif as $s) {
                $sumatifBySiswa[$s['siswa_id']][] = $s;
            }

            // 5. Kalkulasi untuk setiap siswa
            foreach ($siswaList as $siswa) {
                $siswaId = (int)$siswa['id'];
                $sFormatif = $formatifBySiswa[$siswaId] ?? [];
                $sSumatif   = $sumatifBySiswa[$siswaId] ?? [];

                $sumNh = 0; $sumUh = 0;
                $sumSts = 0; $countSts = 0;
                $sumPas = 0; $countPas = 0;
                $sumSas = 0; $countSas = 0;
                $lmScores = [];

                foreach ($sFormatif as $f) {
                    $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
                    $nilai = (float)($f['nilai_angka'] ?? 0);
                    $pert  = (int)($f['pertemuan'] ?? 0);

                    if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
                        $sumUh += $nilai;
                    } else {
                        $sumNh += $nilai;
                    }

                    if (!isset($lmScores[$pert])) $lmScores[$pert] = ['sum' => 0, 'count' => 0];
                    $lmScores[$pert]['sum'] += $nilai;
                    $lmScores[$pert]['count']++;
                }

                foreach ($sSumatif as $s) {
                    $jenis = strtoupper(trim($s['jenis_sumatif'] ?? ''));
                    $nilai = (float)($s['nilai'] ?? 0);

                    if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
                        $sumSts += $nilai;
                        $countSts++;
                    } elseif (strpos($jenis, 'PAS') !== false) {
                        $sumPas += $nilai;
                        $countPas++;
                    } elseif (strpos($jenis, 'SAS') !== false) {
                        $sumSas += $nilai;
                        $countSas++;
                    }
                }

                $avgNh  = $sumNh / $pembagi['nh'];
                $avgUh  = $sumUh / $pembagi['uh'];
                $avgSts = $countSts > 0 ? ($sumSts / $countSts) : 0;
                $avgPas = $countPas > 0 ? ($sumPas / $countPas) : 0;
                $avgSas = $countSas > 0 ? ($sumSas / $countSas) : 0;

                if ($kategori === 'Tengah Semester') {
                    $wNh  = $bobot['tengah_semester']['nh'] / 100.0;
                    $wUh  = $bobot['tengah_semester']['uh'] / 100.0;
                    $wSts = $bobot['tengah_semester']['sts'] / 100.0;

                    $kalkulasi    = ($avgNh * $wNh) + ($avgUh * $wUh) + ($avgSts * $wSts);
                    $rataFormatif = round(($avgNh + $avgUh) / 2.0, 1);
                    $rataSumatif  = round($avgSts, 1);
                } else {
                    $wNh  = $bobot['akhir_semester']['nh'] / 100.0;
                    $wUh  = $bobot['akhir_semester']['uh'] / 100.0;
                    $wSts = $bobot['akhir_semester']['sts'] / 100.0;
                    $wSas = $bobot['akhir_semester']['sas'] / 100.0;

                    $cNh  = floor(round($avgNh * $wNh, 4) * 10) / 10;
                    $cUh  = floor(round($avgUh * $wUh, 4) * 10) / 10;
                    $cSts = floor(round($avgPas * $wSts, 4) * 10) / 10;
                    $cSas = floor(round($avgSas * $wSas, 4) * 10) / 10;

                    $kalkulasi    = $cNh + $cUh + $cSts + $cSas;
                    $rataFormatif = round(($avgNh + $avgUh) / 2.0, 1);
                    $rataSumatif  = round(($avgPas + $avgSas) / 2.0, 1);
                }

                $nilaiAkhir = (float)number_format($kalkulasi, 1, '.', '');

                // Tentukan Predikat
                $predikat = '-';
                if (!empty($aturanPredikat)) {
                    foreach ($aturanPredikat as $aturan) {
                        if (floor($nilaiAkhir) >= $aturan['nilai_min'] && floor($nilaiAkhir) <= $aturan['nilai_max']) {
                            $predikat = $aturan['deskripsi_predikat'];
                            break;
                        }
                    }
                    if ($predikat === '-') $predikat = 'Perlu Bimbingan';
                } else {
                    if ($nilaiAkhir >= 90)     $predikat = 'Sangat Baik';
                    elseif ($nilaiAkhir >= 80) $predikat = 'Baik';
                    elseif ($nilaiAkhir >= 70) $predikat = 'Cukup';
                    else                       $predikat = 'Perlu Bimbingan';
                }

                // Tentukan Deskripsi Capaian LM Tertinggi & Terendah
                $maxScore = -1; $maxPert = null;
                $minScore = 101; $minPert = null;
                foreach ($lmScores as $pert => $dataScore) {
                    $avgPert = $dataScore['sum'] / $dataScore['count'];
                    if ($avgPert > $maxScore) { $maxScore = $avgPert; $maxPert = $pert; }
                    if ($avgPert < $minScore) { $minScore = $avgPert; $minPert = $pert; }
                }

                $deskripsiTertinggi = '';
                $deskripsiTerendah  = '';
                foreach ($allLMs as $lm) {
                    $angkaLm = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                    $materi  = trim(lcfirst($lm['deskripsi_lm'] ?? ''));
                    if ($materi) {
                        if ($angkaLm === $maxPert) {
                            $deskripsiTertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . $materi;
                        }
                        if ($angkaLm === $minPert && $minScore < 75) {
                            $deskripsiTerendah = "Perlu pendampingan lebih lanjut dalam " . $materi;
                        }
                    }
                }

                $deskripsiGabungan = trim($deskripsiTertinggi . ($deskripsiTerendah ? ' ' . $deskripsiTerendah : ''));

                // Upsert nilai_rapor
                $stmtCheck = $pdo->prepare("
                    SELECT id FROM nilai_rapor 
                    WHERE siswa_id = ? AND tahun_ajaran_id = ? AND mapel_id = ? AND kategori = ?
                    LIMIT 1
                ");
                $stmtCheck->execute([$siswaId, $taId, $mapelId, $kategori]);
                $existing = $stmtCheck->fetch();

                if ($existing) {
                    $stmtUpdate = $pdo->prepare("
                        UPDATE nilai_rapor 
                        SET rombel_id = ?, rata_formatif = ?, rata_sumatif = ?, nilai_akhir = ?, predikat = ?, deskripsi = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmtUpdate->execute([$rombelId, $rataFormatif, $rataSumatif, $nilaiAkhir, $predikat, $deskripsiGabungan, $existing['id']]);
                } else {
                    $stmtInsert = $pdo->prepare("
                        INSERT INTO nilai_rapor (siswa_id, rombel_id, tahun_ajaran_id, mapel_id, kategori, rata_formatif, rata_sumatif, nilai_akhir, predikat, deskripsi, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                    ");
                    $stmtInsert->execute([$siswaId, $rombelId, $taId, $mapelId, $kategori, $rataFormatif, $rataSumatif, $nilaiAkhir, $predikat, $deskripsiGabungan]);
                }

                $nilaiRaporRombel++;
            }
        }

        $pdo->commit();
        $totalSiswaDisinkron += count($siswaList);
        $totalNilaiRaporDisinkron += $nilaiRaporRombel;
        $totalRombelSukses++;

        echo sprintf("     [OK] %d Siswa | %d Mapel | %d Rekor Nilai Rapor berhasil disinkronkan.\n\n",
            count($siswaList), count($listMapel), $nilaiRaporRombel
        );

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "     [ERROR] Gagal memproses rombel {$rombelName}: " . $e->getMessage() . "\n\n";
    }
}

$elapsedTime = round(microtime(true) - $startTimeTotal, 2);

// ------------------------------------------------------------------------------
// 8. RINGKASAN AKHIR
// ------------------------------------------------------------------------------
echo "==============================================================================\n";
echo "                         SINKRONISASI SELESAI                                \n";
echo "==============================================================================\n";
echo "  Total Rombel Diproses   : {$totalRombelSukses} dari " . count($rombelList) . " kelas\n";
echo "  Total Siswa Terdampak   : {$totalSiswaDisinkron} siswa\n";
echo "  Total Rekor Nilai Rapor : {$totalNilaiRaporDisinkron} nilai ter-update/insert\n";
echo "  Waktu Eksekusi          : {$elapsedTime} detik\n";
echo "==============================================================================\n\n";

