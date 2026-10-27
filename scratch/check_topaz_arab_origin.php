<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// Cari nilai formatif Topaz untuk Bahasa Arab (Mapel 10) dan Nahwu (Mapel 44)
$stmt = $pdo->query("SELECT DISTINCT nf.guru_id, nf.created_at, g.nama_lengkap FROM nilai_formatif nf LEFT JOIN guru_tendik g ON g.id = nf.guru_id WHERE nf.rombel_id = 73 AND nf.mapel_id IN (10, 44) AND nf.tahun_ajaran_id = 10");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

// Cek siapa saja siswa Topaz yang punya nilai formatif B. Arab
$stmt2 = $pdo->query("SELECT s.id, s.nama_lengkap FROM siswa s JOIN nilai_formatif nf ON nf.siswa_id = s.id WHERE nf.rombel_id = 73 AND nf.mapel_id = 10 AND nf.tahun_ajaran_id = 10 GROUP BY s.id, s.nama_lengkap ORDER BY s.nama_lengkap");
$arab_students = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo "Jumlah siswa Topaz yang punya nilai B. Arab: " . count($arab_students) . "\n";
foreach ($arab_students as $st) {
    echo "- ID {$st['id']}: {$st['nama_lengkap']}\n";
}

