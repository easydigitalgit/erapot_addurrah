<?php
$siswa = json_decode(file_get_contents('scratch/missing_siswa_detail.json'), true);
echo "Total missing siswa di VPS: " . count($siswa) . "\n";
foreach ($siswa as $s) {
    echo sprintf("ID %-4d | Kelas %-10s | NIS %-11s | %-28s | Sumatif: %-3d | Formatif: %-3d | Rapor: %-3d\n", 
        $s['id'], $s['nama_rombel'], $s['nis'], $s['nama_lengkap'], $s['sumatif_count'], $s['formatif_count'], $s['rapor_count']);
}

$lm = json_decode(file_get_contents('scratch/missing_lm_detail.json'), true);
echo "\nTotal missing LM di VPS: " . count($lm) . "\n";
$lmByTingkat = [];
foreach ($lm as $l) {
    $k = "Tingkat {$l['tingkat']} | {$l['semester']} | {$l['kategori']}";
    $lmByTingkat[$k][] = "{$l['nama_mapel']} ({$l['kode_lm']})";
}
foreach ($lmByTingkat as $k => $list) {
    echo "$k (" . count($list) . " LM):\n";
    echo "  " . implode(", ", array_slice($list, 0, 8)) . (count($list) > 8 ? " ... (total " . count($list) . ")" : "") . "\n";
}

