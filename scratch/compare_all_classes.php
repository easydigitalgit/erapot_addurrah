<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

$sheetsToScan = ['VII', 'VIII', 'IX'];
$excelClasses = [];

foreach ($sheetsToScan as $sName) {
    $sheet = $spreadsheet->getSheetByName($sName);
    $highestRow = $sheet->getHighestDataRow();
    $currentClass = null;
    
    for ($r = 1; $r <= $highestRow; $r++) {
        $valA = trim((string)$sheet->getCell('A' . $r)->getValue());
        $valB = trim((string)$sheet->getCell('B' . $r)->getValue());
        $valC = trim((string)$sheet->getCell('C' . $r)->getValue());
        $valD = trim((string)$sheet->getCell('D' . $r)->getValue());
        $valAH = trim((string)$sheet->getCell('AH' . $r)->getValue());

        if (preg_match('/^(VII|VIII|IX)\s*[-_ ]*\s*(AMAZONITE|OPAL|ZAMRUD|CARNELIAN|RUBY|GRANIT|TOPAZ|INTAN|KYANITE|SAFIR|EMERALD|FAIRUZ|BIDURI|CRYSTAL|DIAMOND)$/i', $valA, $m) ||
            preg_match('/^(AMAZONITE|OPAL|ZAMRUD|CARNELIAN|RUBY|GRANIT|TOPAZ|INTAN|KYANITE|SAFIR|EMERALD|FAIRUZ|BIDURI|CRYSTAL|DIAMOND)$/i', $valA, $m)) {
            $className = strtoupper($m[1] . (isset($m[2]) ? ' ' . $m[2] : ''));
            if (!preg_match('/^(VII|VIII|IX)/', $className)) {
                $className = $sName . ' ' . $className;
            }
            $currentClass = $className;
            $excelClasses[$currentClass] = [];
            continue;
        }

        if (is_numeric($valA) && !empty($valB) && $valB !== 'Nama' && $currentClass) {
            $excelClasses[$currentClass][] = [
                'row' => $r,
                'no' => (int)$valA,
                'nama' => $valB,
                'nipd' => $valC,
                'nisn' => $valD,
                'rombel_saat_ini' => $valAH
            ];
        }
    }
}

$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');

// Mapping Class Name to rombel_id in DB (TA 10)
$rombelMap = [
    'VII AMAZONITE' => 77,
    'VII OPAL'      => 78,
    'VII ZAMRUD'    => 79,
    'VII CARNELIAN' => 80,
    'VII RUBY'      => 81,
    'VIII GRANIT'   => 72,
    'VIII TOPAZ'    => 73,
    'VIII INTAN'    => 74,
    'VIII KYANITE'  => 75,
    'VIII SAFIR'    => 76,
    'IX FAIRUZ'     => 67,
    'IX EMERALD'    => 68,
    'IX BIDURI'     => 69,
    'IX CRYSTAL'    => 70,
    'IX DIAMOND'    => 71,
];

echo "========================================================================\n";
echo "HASIL ANALISIS KELAS BY KELAS: EXCEL DAPODIK VS DATABASE SAAT INI (TA 10)\n";
echo "========================================================================\n\n";

$summary = [];

foreach ($rombelMap as $cName => $rid) {
    echo "------------------------------------------------------------------------\n";
    echo "KELAS: $cName (Rombel ID DB: $rid)\n";
    echo "------------------------------------------------------------------------\n";

    $excelList = $excelClasses[$cName] ?? [];
    $totalExcel = count($excelList);

    // Ambil data anggota_rombel di DB
    $dbAR = $mysqli->query("SELECT ar.id as ar_id, ar.siswa_id, s.id as s_id, s.nis, s.nisn, s.nama_lengkap, s.status_siswa
                            FROM anggota_rombel ar
                            LEFT JOIN siswa s ON ar.siswa_id = s.id
                            WHERE ar.rombel_id = $rid")->fetch_all(MYSQLI_ASSOC);

    $totalAR = count($dbAR);
    $totalSiswaAktif = 0;
    $orphanAR = [];
    $dbSiswaMap = [];

    foreach ($dbAR as $row) {
        if ($row['s_id']) {
            $totalSiswaAktif++;
            $dbSiswaMap[$row['siswa_id']] = $row;
        } else {
            $orphanAR[] = $row;
        }
    }

    echo "Jumlah Siswa Excel Dapodik : $totalExcel\n";
    echo "Jumlah Siswa Anggota Rombel: $totalAR\n";
    echo "Jumlah Siswa Valid di Master: $totalSiswaAktif\n";
    echo "Jumlah Siswa Orphan (Hilang di Master Siswa): " . count($orphanAR) . "\n";

    // Cek setiap siswa Excel apakah ada di DB
    $missingInDB = [];
    $matchedInDB = [];

    foreach ($excelList as $eSiswa) {
        $eNama = trim($eSiswa['nama']);
        $eNis = trim($eSiswa['nipd']);
        $eNisn = trim($eSiswa['nisn']);

        $found = false;
        // Cari di dbAR yang valid
        foreach ($dbAR as $dRow) {
            if ($dRow['s_id']) {
                $dNama = trim($dRow['nama_lengkap']);
                $dNis = trim($dRow['nis']);
                $dNisn = trim($dRow['nisn']);

                if (strcasecmp($dNama, $eNama) === 0 || 
                    (!empty($dNis) && $dNis === $eNis) || 
                    (!empty($dNisn) && $dNisn === $eNisn) ||
                    (stripos($dNama, $eNama) !== false || stripos($eNama, $dNama) !== false)) {
                    $found = true;
                    $matchedInDB[] = [
                        'excel' => $eSiswa,
                        'db' => $dRow
                    ];
                    break;
                }
            }
        }

        if (!$found) {
            // Cek apakah ada di tabel siswa secara global tapi beda rombel atau belum masuk rombel
            $eNamaEsc = $mysqli->real_escape_string($eNama);
            $globalSiswa = $mysqli->query("SELECT id, nis, nisn, nama_lengkap, rombel_id FROM siswa WHERE nama_lengkap LIKE '%$eNamaEsc%' LIMIT 1")->fetch_assoc();
            $missingInDB[] = [
                'excel' => $eSiswa,
                'global' => $globalSiswa
            ];
        }
    }

    // Cek siswa di DB yang tidak ada di Excel
    $extraInDB = [];
    foreach ($dbAR as $dRow) {
        $found = false;
        $dNama = trim($dRow['nama_lengkap'] ?? '');
        $dNis = trim($dRow['nis'] ?? '');
        $dNisn = trim($dRow['nisn'] ?? '');

        foreach ($excelList as $eSiswa) {
            $eNama = trim($eSiswa['nama']);
            $eNis = trim($eSiswa['nipd']);
            $eNisn = trim($eSiswa['nisn']);

            if (($dNama && (strcasecmp($dNama, $eNama) === 0 || stripos($dNama, $eNama) !== false || stripos($eNama, $dNama) !== false)) ||
                (!empty($dNis) && $dNis === $eNis) ||
                (!empty($dNisn) && $dNisn === $eNisn)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $extraInDB[] = $dRow;
        }
    }

    echo "Siswa Excel yang Cocok di Rombel: " . count($matchedInDB) . "\n";
    if (!empty($missingInDB)) {
        echo "⚠️  Siswa di Excel tapi TIDAK DITEMUKAN di Rombel DB (" . count($missingInDB) . " siswa):\n";
        foreach ($missingInDB as $m) {
            $ex = $m['excel'];
            $gl = $m['global'];
            $glInfo = $gl ? "Ada di Master Siswa ID {$gl['id']} (NIS: {$gl['nis']}, Rombel: {$gl['rombel_id']})" : "TIDAK ADA DI MASTER SISWA";
            echo "   - [Excel No {$ex['no']}] {$ex['nama']} (NIS: {$ex['nipd']}, NISN: {$ex['nisn']}) => $glInfo\n";
        }
    } else {
        echo "✅ Semua siswa Excel ada di Rombel DB.\n";
    }

    if (!empty($extraInDB)) {
        echo "⚠️  Siswa di DB yang TIDAK ADA di Daftar Kelas Excel (" . count($extraInDB) . " siswa):\n";
        foreach ($extraInDB as $ex) {
            $nama = $ex['nama_lengkap'] ?? "ORPHAN (siswa_id={$ex['siswa_id']})";
            echo "   - Siswa ID: {$ex['siswa_id']} | {$nama} | NIS: {$ex['nis']}\n";
        }
    } else {
        echo "✅ Tidak ada siswa ekstra di Rombel DB.\n";
    }

    $summary[$cName] = [
        'excel' => $totalExcel,
        'db_ar' => $totalAR,
        'db_active' => $totalSiswaAktif,
        'orphan' => count($orphanAR),
        'missing' => count($missingInDB),
        'extra' => count($extraInDB)
    ];

    echo "\n";
}

echo "========================================================================\n";
echo "TABEL REKAPITULASI KESELURUHAN KELAS\n";
echo "========================================================================\n";
printf("%-16s | %-5s | %-6s | %-6s | %-6s | %-7s | %-6s\n", "Nama Kelas", "Excel", "AR DB", "Aktif", "Orphan", "Missing", "Extra");
echo "------------------------------------------------------------------------\n";
foreach ($summary as $cName => $s) {
    printf("%-16s | %5d | %6d | %6d | %6d | %7d | %6d\n",
        $cName,
        $s['excel'],
        $s['db_ar'],
        $s['db_active'],
        $s['orphan'],
        $s['missing'],
        $s['extra']
    );
}
echo "========================================================================\n";
