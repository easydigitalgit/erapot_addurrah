<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK SISWA DENGAN rombel_id = 76 DI TABEL SISWA ===\n";
$res = $mysqli->query("SELECT id, nis, nama_lengkap, status_siswa, rombel_id FROM siswa WHERE rombel_id = 76");
echo "Total rows: " . $res->num_rows . "\n";
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | NIS: {$r['nis']} | Nama: {$r['nama_lengkap']} | Status: {$r['status_siswa']}\n";
}

echo "\n=== CEK SISWA DENGAN anggota_rombel rombel_id = 76 ===\n";
$res2 = $mysqli->query("SELECT ar.siswa_id, s.id as sid, s.nis, s.nama_lengkap, s.status_siswa, s.rombel_id 
                        FROM anggota_rombel ar 
                        LEFT JOIN siswa s ON ar.siswa_id = s.id 
                        WHERE ar.rombel_id = 76");
echo "Total rows di anggota_rombel: " . $res2->num_rows . "\n";
while ($r = $res2->fetch_assoc()) {
    echo "ar.siswa_id: {$r['siswa_id']} | s.id: " . ($r['sid'] ?? 'NULL') . " | NIS: {$r['nis']} | Nama: {$r['nama_lengkap']} | s.rombel_id: {$r['rombel_id']} | Status: {$r['status_siswa']}\n";
}
