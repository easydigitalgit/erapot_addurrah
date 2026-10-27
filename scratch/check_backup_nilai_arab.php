<?php
// Check nilai in raporsmpit_local_pre_repair_20261027_2309.sql and docs/db271026-2149
$files = [
    'pre_repair' => 'd:/laragon/htdocs/erapoteasy/backups/raporsmpit_local_pre_repair_20261027_2309.sql',
    'vps_271026_2149' => 'd:/laragon/htdocs/erapoteasy/docs/db271026-2149/raporsmpit.sql',
    'old_201026' => 'd:/laragon/htdocs/erapoteasy/raporsmpit-201026-0837.sql',
];

// Target rombel kelas VII ikhwan: 77 (Amazonite), 78 (Opal), 79 (Zamrud)
// Target mapel: 10 (Bahasa Arab), 44 (Nahwu)
// Target siswa: 532 (Al Buqhori Rafif)

foreach ($files as $name => $path) {
    echo "=== Checking $name ($path) ===\n";
    if (!file_exists($path)) {
        echo "File does not exist\n";
        continue;
    }
    
    // We can search lines in nilai_sumatif, nilai_formatif, nilai_rapor
    // Since SQL dumps have INSERT INTO `nilai_rapor` VALUES (...), (...);
    // Let's inspect by reading stream or regex.
    $handle = fopen($path, 'r');
    if (!$handle) {
        echo "Cannot open file\n";
        continue;
    }

    $found_formatif = 0;
    $found_sumatif = 0;
    $found_rapor = 0;
    $table = '';

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
            // Check for siswa 532 or rombel 77/78/79 with mapel 10 or 44
            // format: (id, siswa_id, rombel_id, mapel_id, ...)
            // Let's do regex matching
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $s_id = (int)$m[2];
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[4];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $found_rapor++;
                        echo "Found in $table: Siswa $s_id, Rombel $r_id, Mapel $m_id\n";
                    }
                }
            }
        } elseif ($table === 'nilai_sumatif') {
            // format: (id, siswa_id, rombel_id, mapel_id, ...) or similar
            // Let's check table structure from first line if needed, or regex
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $s_id = (int)$m[2];
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[4];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $found_sumatif++;
                    }
                }
            }
        } elseif ($table === 'nilai_formatif') {
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $s_id = (int)$m[2];
                    $r_id = (int)$m[3];
                    $m_id = (int)$m[4];
                    if (in_array($r_id, [77, 78, 79]) && in_array($m_id, [10, 44])) {
                        $found_formatif++;
                    }
                }
            }
        }
    }
    fclose($handle);
    echo "Summary $name: Formatif = $found_formatif, Sumatif = $found_sumatif, Rapor = $found_rapor\n\n";
}

