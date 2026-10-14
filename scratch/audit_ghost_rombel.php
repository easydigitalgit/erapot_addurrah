<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== DAFTAR SEMUA SISWA CARNELIAN (TOTAL 33) ===\n";
$stmt = $pdo->query("
    SELECT s.id, s.nama_lengkap, s.nis, s.nisn, s.rombel_id, r.nama_rombel, r.id_tahun_ajaran,
           ar.rombel_id as ar_rombel_id, ar.tahun_ajaran_id as ar_ta_id, ar.created_at as ar_created
    FROM siswa s
    LEFT JOIN rombel r ON r.id = s.rombel_id
    LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id
    WHERE s.rombel_id IN (55, 80) OR ar.rombel_id IN (55, 80)
    ORDER BY s.rombel_id ASC, s.nama_lengkap ASC
");
$carnelian = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total row ditemukan: " . count($carnelian) . "\n\n";

$rombel55 = [];
$rombel80 = [];
foreach ($carnelian as $c) {
    if ($c['rombel_id'] == 55 || $c['ar_rombel_id'] == 55) {
        $rombel55[] = $c;
    } else {
        $rombel80[] = $c;
    }
}

echo "--- SISWA DI ROMBEL 55 (Total: " . count($rombel55) . ") ---\n";
foreach ($rombel55 as $r) {
    echo "ID: {$r['id']} | NIS: {$r['nis']} | NISN: {$r['nisn']} | Rombel: {$r['rombel_id']} | AR_Rombel: {$r['ar_rombel_id']} | AR_TA: {$r['ar_ta_id']} | Nama: {$r['nama_lengkap']}\n";
}

echo "\n--- SISWA DI ROMBEL 80 (Total: " . count($rombel80) . ") ---\n";
foreach ($rombel80 as $r) {
    echo "ID: {$r['id']} | NIS: {$r['nis']} | NISN: {$r['nisn']} | Rombel: {$r['rombel_id']} | AR_Rombel: {$r['ar_rombel_id']} | AR_TA: {$r['ar_ta_id']} | Nama: {$r['nama_lengkap']}\n";
}

echo "\n=== CEK KELAS LAIN: APAKAH ADA SISWA YANG TERCAMPUR DI ROMBEL 52, 53, 54, 56? ===\n";
$otherGhost = $pdo->query("SELECT s.id, s.nama_lengkap, s.rombel_id, r.nama_rombel, r.tingkat FROM siswa s JOIN rombel r ON r.id = s.rombel_id WHERE s.rombel_id IN (52, 53, 54, 56)")->fetchAll(PDO::FETCH_ASSOC);
echo "Siswa di rombel ghost (52,53,54,56): " . count($otherGhost) . "\n";
print_r($otherGhost);
