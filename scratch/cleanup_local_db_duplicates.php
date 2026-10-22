<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');

$restoredIds = [
    540, 550, 551, 451, 453, 461, 439, 440, 454, 629, 630, 6, 29, 36, 39, 
    40, 46, 51, 52, 54, 68, 74, 91, 94, 99, 164, 166, 176, 187, 137, 
    208, 209, 211, 222, 233, 243, 246, 256, 267
];

// 1. Bersihkan duplikat di orangtua_wali, sisakan ID terbesar per siswa_id
foreach ($restoredIds as $sid) {
    $stmt = $pdo->prepare("SELECT id FROM orangtua_wali WHERE siswa_id = ? ORDER BY id DESC");
    $stmt->execute([$sid]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (count($ids) > 1) {
        $keep = array_shift($ids); // ambil ID terbaru
        $delClause = implode(',', $ids);
        $pdo->exec("DELETE FROM orangtua_wali WHERE id IN ($delClause)");
    }
}

// 2. Bersihkan dangling anggota_rombel jika masih ada
$pdo->exec("DELETE FROM anggota_rombel WHERE id IN (747, 749, 615)");

// 3. Verifikasi ulang
$inClause = implode(',', $restoredIds);
$stmt = $pdo->query("SELECT siswa_id, COUNT(*) as jml FROM orangtua_wali WHERE siswa_id IN ($inClause) GROUP BY siswa_id");
$foundOrtu = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$duplikatOrtu = array_filter($foundOrtu, fn($jml) => $jml > 1);

echo "Total orangtua_wali tersimpan: " . count($foundOrtu) . "\n";
echo "Siswa dengan orangtua ganda: " . count($duplikatOrtu) . "\n";

// Cek rombel TA 10
$stmt = $pdo->query("
    SELECT COUNT(DISTINCT ar.siswa_id) 
    FROM anggota_rombel ar 
    JOIN siswa s ON ar.siswa_id = s.id 
    WHERE ar.tahun_ajaran_id = 10 AND s.status_siswa = 'Aktif'
");
echo "Total siswa aktif di rombel TA 10 (Local DB): " . $stmt->fetchColumn() . "\n";
