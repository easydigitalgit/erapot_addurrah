<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CEK TABEL LAIN DI ROMBEL 76 ===\n";

$tables = ['absensi_siswa', 'catatan_wali_kelas', 'nilai_ekstrakurikuler', 'nilai_karakter', 'prestasi_siswa'];
foreach ($tables as $t) {
    $res = $mysqli->query("SHOW TABLES LIKE '$t'");
    if ($res->num_rows > 0) {
        $count = $mysqli->query("SELECT COUNT(*) as c FROM $t WHERE rombel_id = 76")->fetch_assoc()['c'] ?? 0;
        echo "$t: $count baris di rombel 76\n";
    }
}

// Check how many students in rombel 76 have absensi or catatan
$resAbs = $mysqli->query("SHOW TABLES LIKE 'absensi'");
if ($resAbs->num_rows > 0) {
    $count = $mysqli->query("SELECT COUNT(*) as c FROM absensi WHERE rombel_id = 76")->fetch_assoc()['c'] ?? 0;
    echo "absensi: $count baris di rombel 76\n";
}

// Check if any student in rombel 76 has missing data in specific mapel
echo "\n=== CEK KELENGKAPAN NILAI PER SISWA DI ROMBEL 76 ===\n";
$resS = $mysqli->query("SELECT ar.siswa_id, s.nama_lengkap, s.nis,
                        (SELECT COUNT(DISTINCT nf.mapel_id) FROM nilai_formatif nf WHERE nf.rombel_id=76 AND nf.siswa_id=ar.siswa_id) as mapel_formatif,
                        (SELECT COUNT(DISTINCT nr.mapel_id) FROM nilai_rapor nr WHERE nr.rombel_id=76 AND nr.siswa_id=ar.siswa_id) as mapel_rapor,
                        (SELECT COUNT(DISTINCT ns.mapel_id) FROM nilai_sumatif ns WHERE ns.tahun_ajaran_id=10 AND ns.siswa_id=ar.siswa_id) as mapel_sumatif
                        FROM anggota_rombel ar
                        LEFT JOIN siswa s ON s.id = ar.siswa_id
                        WHERE ar.rombel_id = 76
                        ORDER BY ar.siswa_id ASC");
while ($r = $resS->fetch_assoc()) {
    echo "Siswa ID {$r['siswa_id']} | " . ($r['nama_lengkap'] ?? '[TERHAPUS DARI SISWA]') . 
         " | NIS: {$r['nis']} | Mapel Formatif: {$r['mapel_formatif']} | Mapel Rapor: {$r['mapel_rapor']} | Mapel Sumatif: {$r['mapel_sumatif']}\n";
}
