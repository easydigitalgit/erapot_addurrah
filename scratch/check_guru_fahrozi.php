<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');

// Check guru_id 34
$guru = $pdo->query("SELECT * FROM guru_tendik WHERE id = 34")->fetch(PDO::FETCH_ASSOC);
echo "Guru 34: " . ($guru['nama_lengkap'] ?? 'Not found') . "\n";

// Check guru_mapel for guru 34
$gm = $pdo->query("SELECT gm.*, m.nama_mapel, r.nama_rombel FROM guru_mapel gm 
    JOIN mata_pelajaran m ON gm.mapel_id = m.id 
    JOIN rombel r ON gm.rombel_id = r.id 
    WHERE gm.guru_id = 34")->fetchAll(PDO::FETCH_ASSOC);
echo "Penugasan guru 34: \n";
foreach ($gm as $row) {
    echo "  - Mapel: {$row['nama_mapel']} (ID {$row['mapel_id']}), Rombel: {$row['nama_rombel']} (ID {$row['rombel_id']})\n";
}

// Check formatif by guru 34
$stmt = $pdo->query("SELECT count(*) as cnt FROM nilai_formatif WHERE guru_id = 34");
$cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
echo "Total nilai_formatif by guru 34: $cnt\n";

// Check if any formatif for mapel 10 or 44 across all teachers
$stmt = $pdo->query("SELECT f.guru_id, g.nama_lengkap, f.rombel_id, r.nama_rombel, f.mapel_id, m.nama_mapel, count(*) as cnt 
    FROM nilai_formatif f
    LEFT JOIN guru_tendik g ON f.guru_id = g.id
    LEFT JOIN rombel r ON f.rombel_id = r.id
    LEFT JOIN mata_pelajaran m ON f.mapel_id = m.id
    WHERE f.mapel_id IN (10, 44)
    GROUP BY f.guru_id, f.rombel_id, f.mapel_id");
echo "Formatif Bahasa Arab / Nahwu di sistem:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  - Guru: {$row['nama_lengkap']} | Rombel: {$row['nama_rombel']} | Mapel: {$row['nama_mapel']} | Jumlah: {$row['cnt']}\n";
}
