<?php
$data = json_decode(file_get_contents('scratch/detailed_report.json'), true);

echo "==================================================\n";
echo "1. TAHUN AJARAN & ROMBEL\n";
echo "==================================================\n";
echo "Lokal TA Aktif: ID " . $data['tahun_ajaran']['local_active']['id'] . " (" . $data['tahun_ajaran']['local_active']['tahun'] . " " . $data['tahun_ajaran']['local_active']['semester'] . ")\n";
echo "VPS TA Aktif:   ID " . $data['tahun_ajaran']['vps_active']['id'] . " (" . $data['tahun_ajaran']['vps_active']['tahun'] . " " . $data['tahun_ajaran']['vps_active']['semester'] . ")\n";
echo "Rombel Diff: " . count($data['rombel']['diff']) . " perbedaan\n";
foreach ($data['rombel']['diff'] as $d) {
    echo "  - $d\n";
}

echo "\n==================================================\n";
echo "2. SISWA PER ROMBEL TAHUN AJARAN AKTIF (ID 10)\n";
echo "==================================================\n";
printf("%-5s | %-15s | %-7s | %-12s | %-12s | %-12s | %-12s\n", "ID", "Nama Rombel", "Tingkat", "AR Lokal", "AR VPS", "SiswaCol Lok", "SiswaCol VPS");
echo str_repeat("-", 85) . "\n";
foreach ($data['siswa_per_rombel_aktif'] as $row) {
    printf("%-5d | %-15s | %-7d | %-12d | %-12d | %-12d | %-12d\n",
        $row['rombel_id'], $row['nama_rombel'], $row['tingkat'],
        $row['jml_ar_local'], $row['jml_ar_vps'],
        $row['jml_siswa_col_local'], $row['jml_siswa_col_vps']
    );
}

echo "\n==================================================\n";
echo "3. SISWA HILANG / BERBEDA\n";
echo "==================================================\n";
echo "Total Siswa Lokal: " . $data['siswa']['local_count'] . "\n";
echo "Total Siswa VPS:   " . $data['siswa']['vps_count'] . "\n";
echo "Siswa di Lokal tapi TIDAK ADA di VPS: " . $data['siswa']['missing_in_vps_count'] . "\n";
foreach ($data['siswa']['missing_in_vps'] as $s) {
    echo "  [ID {$s['id']}] NIS: {$s['nis']}, Nama: {$s['nama_lengkap']}, Status: {$s['status_siswa']}, Rombel: {$s['rombel_id']}\n";
}

echo "Siswa di VPS tapi TIDAK ADA di Lokal: " . $data['siswa']['missing_in_local_count'] . "\n";
foreach ($data['siswa']['missing_in_local'] as $s) {
    echo "  [ID {$s['id']}] NIS: {$s['nis']}, Nama: {$s['nama_lengkap']}, Status: {$s['status_siswa']}, Rombel: {$s['rombel_id']}\n";
}

echo "Siswa dengan ID sama tapi beda data: " . $data['siswa']['mismatched_count'] . "\n";
foreach ($data['siswa']['mismatched_sample'] as $s) {
    echo "  [ID {$s['id']}] NIS: '{$s['nis_loc']}' vs '{$s['nis_vps']}' | Nama: '{$s['nama_loc']}' vs '{$s['nama_vps']}' | Rombel: '{$s['rombel_loc']}' vs '{$s['rombel_vps']}'\n";
}

echo "\n==================================================\n";
echo "4. MASTER LINGKUP MATERI (LM)\n";
echo "==================================================\n";
echo "Total LM Lokal: " . $data['master_lm']['local_total'] . "\n";
echo "Total LM VPS:   " . $data['master_lm']['vps_total'] . "\n";
echo "Distribusi Lokal:\n";
foreach ($data['master_lm']['dist_local'] as $d) {
    echo "  Tingkat {$d['tingkat']} | {$d['semester']} | {$d['kategori']}: {$d['jml']} LM\n";
}
echo "Distribusi VPS:\n";
foreach ($data['master_lm']['dist_vps'] as $d) {
    echo "  Tingkat {$d['tingkat']} | {$d['semester']} | {$d['kategori']}: {$d['jml']} LM\n";
}
echo "LM di Lokal tapi TIDAK ADA di VPS: " . $data['master_lm']['missing_in_vps_count'] . "\n";
echo "LM di VPS tapi TIDAK ADA di Lokal: " . $data['master_lm']['missing_in_local_count'] . "\n";
echo "LM ID sama tapi BEDA isi: " . $data['master_lm']['diff_content_count'] . "\n";

echo "\n==================================================\n";
echo "5. NILAI SUMATIF PER TAHUN AJARAN\n";
echo "==================================================\n";
foreach ($data['nilai_sumatif']['by_ta'] as $r) {
    echo "  TA ID {$r['ta_id']} ({$r['tahun']}): Lokal={$r['jml_lokal']}, VPS={$r['jml_vps']}, Selisih={$r['selisih']}\n";
}

echo "\n==================================================\n";
echo "6. NILAI FORMATIF PER TAHUN AJARAN\n";
echo "==================================================\n";
foreach ($data['nilai_formatif']['by_ta'] as $r) {
    echo "  TA ID {$r['ta_id']} ({$r['tahun']}): Lokal={$r['jml_lokal']}, VPS={$r['jml_vps']}, Selisih={$r['selisih']}\n";
}

echo "\n==================================================\n";
echo "7. NILAI RAPOR PER TAHUN AJARAN\n";
echo "==================================================\n";
foreach ($data['nilai_rapor']['by_ta'] as $r) {
    echo "  TA ID {$r['ta_id']} ({$r['tahun']}): Lokal={$r['jml_lokal']}, VPS={$r['jml_vps']}, Selisih={$r['selisih']}\n";
}

echo "\n==================================================\n";
echo "8. ORPHANED NILAI (mengarah ke siswa yang tidak ada di tabel siswa)\n";
echo "==================================================\n";
print_r($data['orphans']);

