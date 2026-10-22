<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== NILAI FORMATIF DI ROMBEL 76 (VIII SAFIR) PER MAPEL ===\n";
$res = $mysqli->query("SELECT nf.mapel_id, mp.nama_mapel, COUNT(DISTINCT nf.siswa_id) as total_siswa, COUNT(*) as total_nilai
                       FROM nilai_formatif nf 
                       LEFT JOIN mata_pelajaran mp ON nf.mapel_id = mp.id 
                       WHERE nf.rombel_id = 76 
                       GROUP BY nf.mapel_id, mp.nama_mapel");
while ($r = $res->fetch_assoc()) {
    echo "Mapel: " . $r['nama_mapel'] . " (ID " . $r['mapel_id'] . ") => " . $r['total_siswa'] . " siswa (" . $r['total_nilai'] . " baris)\n";
}

echo "\n=== SISWA YANG PUNYA NILAI DI MAPEL TERSEBUT DI ROMBEL 76 ===\n";
// Let's check one mapel, e.g. the first one found
$resMapel = $mysqli->query("SELECT DISTINCT nf.mapel_id, mp.nama_mapel FROM nilai_formatif nf JOIN mata_pelajaran mp ON nf.mapel_id = mp.id WHERE nf.rombel_id = 76 LIMIT 5");
while ($m = $resMapel->fetch_assoc()) {
    $mid = $m['mapel_id'];
    echo "\n--- MAPEL: " . $m['nama_mapel'] . " (ID $mid) ---\n";
    $resSiswa = $mysqli->query("SELECT DISTINCT nf.siswa_id, s.nama_lengkap, s.nis 
                                FROM nilai_formatif nf 
                                LEFT JOIN siswa s ON nf.siswa_id = s.id 
                                WHERE nf.rombel_id = 76 AND nf.mapel_id = $mid
                                ORDER BY s.nama_lengkap ASC");
    while ($s = $resSiswa->fetch_assoc()) {
        echo "Siswa ID: " . $s['siswa_id'] . " | NIS: " . $s['nis'] . " | " . ($s['nama_lengkap'] ?? 'NULL / TERHAPUS') . "\n";
    }
}
