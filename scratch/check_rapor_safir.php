<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== NILAI RAPOR DI ROMBEL 76 (VIII SAFIR) PER MAPEL ===\n";
$res = $mysqli->query("SELECT nr.mapel_id, mp.nama_mapel, COUNT(DISTINCT nr.siswa_id) as total_siswa, COUNT(*) as total_nilai
                       FROM nilai_rapor nr 
                       LEFT JOIN mata_pelajaran mp ON nr.mapel_id = mp.id 
                       WHERE nr.rombel_id = 76 
                       GROUP BY nr.mapel_id, mp.nama_mapel");
while ($r = $res->fetch_assoc()) {
    echo "Mapel: " . $r['nama_mapel'] . " (ID " . $r['mapel_id'] . ") => " . $r['total_siswa'] . " siswa (" . $r['total_nilai'] . " baris)\n";
}

echo "\n=== CEK NILAI FORMATIF DAN RAPOR DI KELAS 8 SAFIR ATAU KELAS 7 SAFIR ===\n";
$res2 = $mysqli->query("SELECT s.id, s.nama_lengkap, s.nis, 
                        (SELECT COUNT(*) FROM nilai_formatif WHERE siswa_id = ar.siswa_id AND rombel_id = 76) as cnt_nf,
                        (SELECT COUNT(*) FROM nilai_rapor WHERE siswa_id = ar.siswa_id AND rombel_id = 76) as cnt_nr
                        FROM anggota_rombel ar 
                        LEFT JOIN siswa s ON ar.siswa_id = s.id 
                        WHERE ar.rombel_id = 76");
while ($r = $res2->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | " . ($r['nama_lengkap'] ?? 'HILANG DI TABEL SISWA') . " | Formatif: " . $r['cnt_nf'] . " | Rapor: " . $r['cnt_nr'] . "\n";
}
