<?php
$path = 'd:/laragon/htdocs/erapoteasy/backups/raporsmpit_local_pre_repair_20261027_2309.sql';
$handle = fopen($path, 'r');
$table = '';
$rows = [];

while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'INSERT INTO `nilai_rapor`') !== false || strpos($line, 'INSERT INTO nilai_rapor') !== false) {
        $table = 'nilai_rapor';
    } elseif (strpos($line, 'CREATE TABLE') !== false) {
        $table = '';
    }

    if ($table === 'nilai_rapor') {
        // Find siswa 532
        if (preg_match_all('/\(([^\)]+)\)/', $line, $matches)) {
            foreach ($matches[1] as $m) {
                $cols = str_getcsv($m, ',', "'");
                // cols: id, siswa_id, rombel_id, mapel_id, ...
                if (count($cols) >= 5) {
                    $s_id = (int)trim($cols[1]);
                    $r_id = (int)trim($cols[2]);
                    $m_id = (int)trim($cols[3]);
                    if ($s_id === 532) {
                        $rows[] = $cols;
                    }
                }
            }
        }
    }
}
fclose($handle);

echo "Siswa 532 in pre_repair backup:\n";
foreach ($rows as $r) {
    echo "ID: {$r[0]}, Siswa: {$r[1]}, Rombel: {$r[2]}, Mapel: {$r[3]}, Nilai: {$r[4]}, Capaian: " . substr($r[5] ?? '', 0, 50) . "...\n";
}

