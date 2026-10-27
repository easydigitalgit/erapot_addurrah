<?php
$files = [
    'pre_repair' => 'd:/laragon/htdocs/erapoteasy/backups/raporsmpit_local_pre_repair_20261027_2309.sql',
    'vps_271026_2149' => 'd:/laragon/htdocs/erapoteasy/docs/db271026-2149/raporsmpit.sql',
    'old_201026' => 'd:/laragon/htdocs/erapoteasy/raporsmpit-201026-0837.sql',
    'auto_121026' => 'd:/laragon/htdocs/erapoteasy/backend/writable/backups/Auto_Full_20261012_130712.sql',
];

// Mapel IDs:
// Bahasa Arab = 10 (or check master_mata_pelajaran)
// Nahwu = 44

foreach ($files as $name => $path) {
    if (!file_exists($path)) continue;
    $handle = fopen($path, 'r');
    $table = '';
    
    $stats = [
        'formatif_77_arab' => 0,
        'formatif_78_arab' => 0,
        'formatif_79_arab' => 0,
        'sumatif_77_arab' => 0,
        'sumatif_78_arab' => 0,
        'sumatif_79_arab' => 0,
        'rapor_77_arab' => 0,
        'rapor_78_arab' => 0,
        'rapor_79_arab' => 0,
    ];

    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `nilai_formatif`') !== false || strpos($line, 'INSERT INTO nilai_formatif') !== false) {
            $table = 'nilai_formatif';
        } elseif (strpos($line, 'INSERT INTO `nilai_sumatif`') !== false || strpos($line, 'INSERT INTO nilai_sumatif') !== false) {
            $table = 'nilai_sumatif';
        } elseif (strpos($line, 'INSERT INTO `nilai_rapor`') !== false || strpos($line, 'INSERT INTO nilai_rapor') !== false) {
            $table = 'nilai_rapor';
        } elseif (strpos($line, 'CREATE TABLE') !== false) {
            $table = '';
        }

        if ($table === 'nilai_rapor') {
            // (id, siswa_id, rombel_id, tahun_ajaran_id, mapel_id, ...)
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[5];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $stats["rapor_{$r_id}_arab"]++;
                    }
                }
            }
        } elseif ($table === 'nilai_sumatif') {
            // Let's check format of nilai_sumatif columns
            // Usually: (id, siswa_id, rombel_id, tahun_ajaran_id, mapel_id, ...)
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[5];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $stats["sumatif_{$r_id}_arab"]++;
                    }
                }
            }
        } elseif ($table === 'nilai_formatif') {
            // Check format
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[5];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $stats["formatif_{$r_id}_arab"]++;
                    }
                }
            }
        }
    }
    fclose($handle);
    echo "=== File: $name ===\n";
    print_r($stats);
}

