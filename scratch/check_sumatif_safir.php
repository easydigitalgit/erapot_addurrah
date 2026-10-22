<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK SISWA DI NILAI_RAPOR DAN NILAI_SUMATIF UNTUK ROMBEL 76 (TA 10) ===\n";
$resS = $mysqli->query("SELECT ar.siswa_id, s.nama_lengkap, s.nis,
                        (SELECT COUNT(*) FROM nilai_sumatif ns WHERE ns.tahun_ajaran_id=10 AND ns.siswa_id=ar.siswa_id) as cnt_sumatif,
                        (SELECT COUNT(*) FROM nilai_formatif nf WHERE nf.rombel_id=76 AND nf.siswa_id=ar.siswa_id) as cnt_formatif,
                        (SELECT COUNT(*) FROM nilai_rapor nr WHERE nr.rombel_id=76 AND nr.siswa_id=ar.siswa_id) as cnt_rapor
                        FROM anggota_rombel ar
                        LEFT JOIN siswa s ON s.id = ar.siswa_id
                        WHERE ar.rombel_id = 76
                        ORDER BY ar.siswa_id ASC");
while ($row = $resS->fetch_assoc()) {
    printf("Siswa ID: %4d | NIS: %-12s | Nama: %-28s | NF: %2d | NS: %2d | NR: %2d\n", 
        $row['siswa_id'], 
        $row['nis'] ?? 'NULL', 
        substr($row['nama_lengkap'] ?? 'TERHAPUS DARI SISWA', 0, 28), 
        $row['cnt_formatif'], 
        $row['cnt_sumatif'], 
        $row['cnt_rapor']
    );
}

echo "\n=== DETAIL NILAI SUMATIF PER MAPEL DI TA 10 UNTUK SISWA ROMBEL 76 ===\n";
$resM = $mysqli->query("SELECT ns.mapel_id, mp.nama_mapel, COUNT(DISTINCT ns.siswa_id) as total_siswa, COUNT(*) as total_nilai
                        FROM nilai_sumatif ns
                        JOIN mata_pelajaran mp ON ns.mapel_id = mp.id
                        WHERE ns.tahun_ajaran_id = 10 
                          AND ns.siswa_id IN (SELECT siswa_id FROM anggota_rombel WHERE rombel_id = 76)
                        GROUP BY ns.mapel_id, mp.nama_mapel");
while ($m = $resM->fetch_assoc()) {
    echo "Mapel: " . $m['nama_mapel'] . " (ID " . $m['mapel_id'] . ") => " . $m['total_siswa'] . " siswa (" . $m['total_nilai'] . " baris)\n";
}
