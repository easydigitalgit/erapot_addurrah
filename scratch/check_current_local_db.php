<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');

echo "=== STATUS DATABASE LOKAL SAAT INI ===\n";

// 1. Cek orphan anggota_rombel
$stmt = $pdo->query('SELECT ar.id, ar.rombel_id, ar.siswa_id, ar.tahun_ajaran_id FROM anggota_rombel ar LEFT JOIN siswa s ON ar.siswa_id = s.id WHERE s.id IS NULL');
$orphans = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "1. Anggota rombel tanpa siswa (orphan): " . count($orphans) . "\n";
foreach ($orphans as $o) {
    echo "   - AR ID: {$o['id']}, Siswa ID: {$o['siswa_id']}, Rombel ID: {$o['rombel_id']}, TA: {$o['tahun_ajaran_id']}\n";
}

// 2. Cek siswa 581-620 yang dulu rusak/tergeser
$stmt = $pdo->query('SELECT COUNT(*) FROM siswa WHERE id BETWEEN 581 AND 620');
echo "2. Sisa siswa error (ID 581-620): " . $stmt->fetchColumn() . "\n";

// 3. Cek orangtua_wali untuk siswa yang dipulihkan (contoh ID 540, 550, 551)
$stmt = $pdo->query('SELECT COUNT(*) FROM orangtua_wali WHERE siswa_id IN (540, 550, 551)');
echo "3. Orangtua_wali untuk ID pemulihan (540, 550, 551): " . $stmt->fetchColumn() . " / 3\n";

// 4. Cek total anggota per rombel di TA 10 (2026/2027)
echo "\n4. Rincian Jumlah Siswa per Rombel:\n";
$stmt = $pdo->query('
    SELECT r.nama_rombel, COUNT(ar.id) as total_siswa 
    FROM rombel r 
    JOIN anggota_rombel ar ON r.id = ar.rombel_id 
    JOIN siswa s ON ar.siswa_id = s.id
    WHERE ar.tahun_ajaran_id = 10 
    GROUP BY r.id 
    ORDER BY r.nama_rombel
');
$total = 0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "   - {$row['nama_rombel']}: {$row['total_siswa']} siswa\n";
    $total += $row['total_siswa'];
}
echo "   TOTAL SISWA AKTIF DI ROMBEL: $total\n";

// 5. Cek siswa ID 42 vs 44
$stmt = $pdo->query('SELECT id, nama_lengkap, nisn FROM siswa WHERE nisn = "3134031119"');
echo "\n5. Siswa dengan NISN 3134031119:\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "   ID {$row['id']} - {$row['nama_lengkap']} (NISN: {$row['nisn']})\n";
}

echo "\n7. Index di tabel orangtua_wali:\n";
$stmt = $pdo->query('SHOW INDEX FROM orangtua_wali');
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "   - {$r['Key_name']} on {$r['Column_name']} (Non_unique: {$r['Non_unique']})\n";
}
