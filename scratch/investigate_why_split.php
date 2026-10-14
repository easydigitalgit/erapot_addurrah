<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== CEK FILE EXCEL APAKAH ADA DI DIRECTORY ===\n";
$files = glob(__DIR__ . '/../*.xlsx');
$files2 = glob(__DIR__ . '/../backend/writable/uploads/*');
$files3 = glob(__DIR__ . '/../backend/public/assets/uploads/*');
$files4 = glob(__DIR__ . '/*.xlsx');
print_r(array_merge($files, $files2, $files3, $files4));

echo "\n=== CEK ANGGOTA ROMBEL UNTUK 33 SISWA CARNELIAN (BERDASARKAN CREATED_AT) ===\n";
$stmt = $pdo->query("
    SELECT ar.id, ar.siswa_id, s.nama_lengkap, s.nis, s.nisn, ar.rombel_id, ar.tahun_ajaran_id, ar.created_at
    FROM anggota_rombel ar
    JOIN siswa s ON s.id = ar.siswa_id
    WHERE ar.rombel_id IN (55, 80)
    ORDER BY ar.created_at ASC
");
$all = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($all as $idx => $r) {
    echo ($idx + 1) . ". [{$r['created_at']}] Siswa: {$r['nama_lengkap']} (ID: {$r['siswa_id']}) -> Rombel ID: {$r['rombel_id']}\n";
}

echo "\n=== CEK ANGGOTA ROMBEL DARI ROMBEL LAIN (MISAL AMBRONITE 77/52, OPAL 78/53, ZAMRUD 79/54, RUBY 81/56) ===\n";
$stmt2 = $pdo->query("
    SELECT r.id, r.nama_rombel, r.tingkat, r.id_tahun_ajaran, min(ar.created_at) as min_c, max(ar.created_at) as max_c, count(ar.id) as total
    FROM rombel r
    LEFT JOIN anggota_rombel ar ON ar.rombel_id = r.id
    WHERE r.id IN (52, 53, 54, 55, 56, 77, 78, 79, 80, 81)
    GROUP BY r.id
    ORDER BY r.id ASC
");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));

echo "\n=== CEK TABEL AUDIT LOG / ACTIVITY LOG JIKA ADA ===\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
$logTables = array_filter($tables, fn($t) => stripos($t, 'log') !== false || stripos($t, 'history') !== false || stripos($t, 'audit') !== false);
print_r($logTables);

