<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

echo "=== DAFTAR SEMUA ROMBEL DI TABEL ROMBEL ===\n";
$stmt = $pdo->query("SELECT id, nama_rombel, tingkat, id_tahun_ajaran, wali_kelas_id FROM rombel ORDER BY id ASC");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Tingkat: {$r['tingkat']} | Nama: {$r['nama_rombel']} | TA: " . ($r['id_tahun_ajaran'] ?? 'NULL') . " | Wali: {$r['wali_kelas_id']}\n";
}
