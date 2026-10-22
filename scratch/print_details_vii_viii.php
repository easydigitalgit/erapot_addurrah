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

$classesVII_VIII = [
    'VII AMAZONITE' => 77,
    'VII OPAL'      => 78,
    'VII CARNELIAN' => 80,
    'VII RUBY'      => 81,
    'VIII GRANIT'   => 72,
    'VIII TOPAZ'    => 73,
    'VIII INTAN'    => 74,
    'VIII KYANITE'  => 75,
];

foreach ($classesVII_VIII as $cName => $rid) {
    echo "========================================================\n";
    echo "KELAS: $cName (Rombel ID $rid)\n";
    echo "========================================================\n";
    
    $excelList = $excelClasses[$cName] ?? [];
    $dbAR = $mysqli->query("SELECT ar.id as ar_id, ar.siswa_id, s.id as s_id, s.nis, s.nisn, s.nama_lengkap
                            FROM anggota_rombel ar
                            LEFT JOIN siswa s ON ar.siswa_id = s.id
                            WHERE ar.rombel_id = $rid")->fetch_all(MYSQLI_ASSOC);

    // Missing from DB
    $missing = [];
    foreach ($excelList as $e) {
        $found = false;
        foreach ($dbAR as $d) {
            if ($d['s_id'] && (stripos($d['nama_lengkap'], $e['nama']) !== false || stripos($e['nama'], $d['nama_lengkap']) !== false)) {
                $found = true;
                break;
            }
        }
        if (!$found) $missing[] = $e;
    }

    // Orphans in DB
    $orphans = [];
    $extras = [];
    foreach ($dbAR as $d) {
        if (!$d['s_id']) {
            $orphans[] = $d['siswa_id'];
        } else {
            $found = false;
            foreach ($excelList as $e) {
                if (stripos($d['nama_lengkap'], $e['nama']) !== false || stripos($e['nama'], $d['nama_lengkap']) !== false) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $extras[] = "ID {$d['siswa_id']} ({$d['nama_lengkap']}, NIS: {$d['nis']})";
        }
    }

    echo "Excel: " . count($excelList) . " | AR DB: " . count($dbAR) . " | Aktif: " . (count($dbAR) - count($orphans)) . " | Orphan: " . count($orphans) . "\n";
    echo "Orphan siswa_id di AR: " . implode(', ', $orphans) . "\n";
    echo "Missing from Excel (" . count($missing) . " siswa):\n";
    foreach ($missing as $m) {
        echo "  - [No {$m['no']}] {$m['nama']} (NIS: {$m['nipd']}, NISN: {$m['nisn']})\n";
    }
    if (!empty($extras)) {
        echo "Extra in DB (" . count($extras) . " siswa):\n";
        foreach ($extras as $ex) echo "  - $ex\n";
    }
    echo "\n";
}
