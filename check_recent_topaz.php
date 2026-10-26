<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'dev', '12345678');

echo "=== CEK NILAI FORMATIF UNTUK KELAS TOPAZ (TA: 10, SEM: Ganjil) ===\n";
// Ambil mapel apa saja yang diupdate baru-baru ini
$stmt = $pdo->query("SELECT nf.mapel_id, mp.nama_mapel, nf.siswa_id, s.nama_lengkap, nf.pertemuan, nf.jenis_penilaian, nf.nilai_angka, nf.rombel_id, nf.updated_at
                     FROM nilai_formatif nf
                     JOIN siswa s ON nf.siswa_id = s.id
                     JOIN mata_pelajaran mp ON nf.mapel_id = mp.id
                     WHERE nf.tahun_ajaran_id = 10 AND nf.semester = 'Ganjil' AND (s.rombel_id = 73 OR nf.rombel_id = 73)
                       AND nf.updated_at >= '2026-10-21 00:00:00'
                     ORDER BY nf.updated_at DESC LIMIT 50");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Jumlah baris formatif baru: " . count($rows) . "\n";
foreach (array_slice($rows, 0, 20) as $r) {
    echo "{$r['updated_at']} | Mapel: {$r['nama_mapel']} | Siswa [{$r['siswa_id']}]: {$r['nama_lengkap']} | Pert: {$r['pertemuan']} ({$r['jenis_penilaian']}) | Nilai: {$r['nilai_angka']} | Rombel: {$r['rombel_id']}\n";
}

echo "\n=== CEK NILAI SUMATIF UNTUK KELAS TOPAZ (TA: 10) ===\n";
$stmtS = $pdo->query("SELECT ns.mapel_id, mp.nama_mapel, ns.siswa_id, s.nama_lengkap, ns.jenis_sumatif, ns.nilai, ns.updated_at
                      FROM nilai_sumatif ns
                      JOIN siswa s ON ns.siswa_id = s.id
                      JOIN mata_pelajaran mp ON ns.mapel_id = mp.id
                      WHERE ns.tahun_ajaran_id = 10 AND s.rombel_id = 73
                        AND ns.updated_at >= '2026-10-21 00:00:00'
                      ORDER BY ns.updated_at DESC LIMIT 50");
$rowsS = $stmtS->fetchAll(PDO::FETCH_ASSOC);
echo "Jumlah baris sumatif baru: " . count($rowsS) . "\n";
foreach (array_slice($rowsS, 0, 20) as $r) {
    echo "{$r['updated_at']} | Mapel: {$r['nama_mapel']} | Siswa [{$r['siswa_id']}]: {$r['nama_lengkap']} | Jenis: {$r['jenis_sumatif']} | Nilai: {$r['nilai']}\n";
}
