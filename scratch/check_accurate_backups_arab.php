<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');

// Get all siswa_id in rombel 77, 78, 79
$stmt = $pdo->query("SELECT siswa_id, rombel_id FROM anggota_rombel WHERE rombel_id IN (77, 78, 79)");
$siswa_rombels = [];
$siswa_ids = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $siswa_rombels[$row['siswa_id']] = $row['rombel_id'];
    $siswa_ids[$row['siswa_id']] = true;
}

echo "Found " . count($siswa_rombels) . " siswa in rombel 77, 78, 79.\n";

$files = [
    'pre_repair' => 'd:/laragon/htdocs/erapoteasy/backups/raporsmpit_local_pre_repair_20261027_2309.sql',
    'vps_271026_2149' => 'd:/laragon/htdocs/erapoteasy/docs/db271026-2149/raporsmpit.sql',
    'old_201026' => 'd:/laragon/htdocs/erapoteasy/raporsmpit-201026-0837.sql',
    'auto_121026' => 'd:/laragon/htdocs/erapoteasy/backend/writable/backups/Auto_Full_20261012_130712.sql',
];

foreach ($files as $name => $path) {
    if (!file_exists($path)) continue;
    $handle = fopen($path, 'r');
    $table = '';
    
    $found_formatif = [];
    $found_sumatif = [];
    $found_rapor = [];

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
                    $s_id = (int)$m[2];
                    $m_id = (int)$m[5];
                    if (isset($siswa_ids[$s_id]) && in_array($m_id, [10, 44])) {
                        $found_rapor[] = "Siswa $s_id, Mapel $m_id";
                    }
                }
            }
        } elseif ($table === 'nilai_sumatif') {
            // (id, siswa_id, mapel_id, ...)
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $s_id = (int)$m[2];
                    $m_id = (int)$m[3];
                    if (isset($siswa_ids[$s_id]) && in_array($m_id, [10, 44])) {
                        $found_sumatif[] = "Siswa $s_id, Mapel $m_id";
                    }
                }
            }
        } elseif ($table === 'nilai_formatif') {
            // (id, siswa_id, guru_id, mapel_id, ...)
            if (preg_match_all('/\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $s_id = (int)$m[2];
                    $m_id = (int)$m[4];
                    if (isset($siswa_ids[$s_id]) && in_array($m_id, [10, 44])) {
                        $found_formatif[] = "Siswa $s_id, Mapel $m_id";
                    }
                }
            }
        }
    }
    fclose($handle);

    echo "=== Backup: $name ===\n";
    echo "Formatif Bahasa Arab / Nahwu: " . count($found_formatif) . "\n";
    echo "Sumatif Bahasa Arab / Nahwu: " . count($found_sumatif) . "\n";
    echo "Rapor Bahasa Arab / Nahwu: " . count($found_rapor) . "\n";
    if (count($found_formatif) > 0) print_r(array_slice($found_formatif, 0, 5));
    if (count($found_sumatif) > 0) print_r(array_slice($found_sumatif, 0, 5));
    if (count($found_rapor) > 0) print_r(array_slice($found_rapor, 0, 5));
    echo "\n";
}

