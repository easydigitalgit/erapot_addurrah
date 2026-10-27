<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$siswa_id = 33;
$ta_id = 10;
$kategori = 'Tengah Semester';

$stmt = $pdo->prepare("
    SELECT m.nomor_urut, m.nama_mapel, nr.nilai_akhir, nr.predikat
    FROM mata_pelajaran m
    LEFT JOIN nilai_rapor nr ON nr.mapel_id = m.id AND nr.siswa_id = ? AND nr.tahun_ajaran_id = ? AND nr.kategori = ?
    WHERE m.status = 'Aktif'
    ORDER BY m.nomor_urut ASC, m.nama_mapel ASC
");
$stmt->execute([$siswa_id, $ta_id, $kategori]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "TAMPILAN HASIL CETAK RAPOR UNTUK ADABI LIMANSYAH SAGALA:\n";
echo str_repeat('-', 70) . "\n";
echo sprintf("%-4s | %-38s | %-12s | %-10s\n", "No", "Mata Pelajaran", "Nilai Akhir", "Predikat");
echo str_repeat('-', 70) . "\n";
$no = 1;
foreach ($rows as $r) {
    if (in_array(strtolower($r['nama_mapel']), ['tahfidz', 'bpi'])) continue;
    $na = $r['nilai_akhir'] !== null ? round($r['nilai_akhir']) : '-';
    echo sprintf("%-4d | %-38s | %-12s | %-10s\n", $no++, $r['nama_mapel'], $na, $r['predikat'] ?? '-');
}
echo str_repeat('-', 70) . "\n";

