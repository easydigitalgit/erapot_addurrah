<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$siswa = $pdo->query("SELECT * FROM siswa WHERE nama_lengkap LIKE '%Buqhori%'")->fetch(PDO::FETCH_ASSOC);
echo "Data Siswa Al Buqhori Rafif:\n";
print_r($siswa);
$sid = $siswa['id'];

$ta_id = 10;
$kategori = 'Tengah Semester';

echo "\nNilai Formatif Al Buqhori Rafif (TA 10):\n";
$formatif = $pdo->query("
    SELECT nf.mapel_id, m.nama_mapel, nf.jenis_penilaian, nf.pertemuan, nf.nilai_angka
    FROM nilai_formatif nf
    JOIN mata_pelajaran m ON m.id = nf.mapel_id
    WHERE nf.siswa_id = $sid AND nf.tahun_ajaran_id = $ta_id
    ORDER BY m.nomor_urut, nf.pertemuan
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($formatif as $f) {
    echo "- [Mapel {$f['mapel_id']} - {$f['nama_mapel']}] P{$f['pertemuan']} {$f['jenis_penilaian']}: {$f['nilai_angka']}\n";
}

echo "\nNilai Sumatif Al Buqhori Rafif (TA 10):\n";
$sumatif = $pdo->query("
    SELECT ns.mapel_id, m.nama_mapel, ns.jenis_sumatif, ns.nilai
    FROM nilai_sumatif ns
    JOIN mata_pelajaran m ON m.id = ns.mapel_id
    WHERE ns.siswa_id = $sid AND ns.tahun_ajaran_id = $ta_id
    ORDER BY m.nomor_urut
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($sumatif as $s) {
    echo "- [Mapel {$s['mapel_id']} - {$s['nama_mapel']}] {$s['jenis_sumatif']}: {$s['nilai']}\n";
}

echo "\nNilai Rapor Al Buqhori Rafif (TA 10):\n";
$rapor = $pdo->query("
    SELECT nr.mapel_id, m.nama_mapel, nr.nilai_akhir, nr.predikat
    FROM nilai_rapor nr
    JOIN mata_pelajaran m ON m.id = nr.mapel_id
    WHERE nr.siswa_id = $sid AND nr.tahun_ajaran_id = $ta_id
    ORDER BY m.nomor_urut
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rapor as $r) {
    echo "- [Mapel {$r['mapel_id']} - {$r['nama_mapel']}]: NA = {$r['nilai_akhir']}, Predikat = {$r['predikat']}\n";
}

// Cek apakah ada nilai Bahasa Arab dan Nahwu untuk rombel Amazonite (ID 77)
echo "\n=== CEK NILAI B. ARAB & NAHWU DI ROMBEL VII AMAZONITE (ID 77) ===\n";
foreach ([10 => 'Bahasa Arab', 44 => 'Nahwu'] as $mid => $mname) {
    $fCount = $pdo->query("SELECT COUNT(*) FROM nilai_formatif nf JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id AND ar.tahun_ajaran_id = $ta_id WHERE ar.rombel_id = 77 AND nf.mapel_id = $mid AND nf.tahun_ajaran_id = $ta_id")->fetchColumn();
    $sCount = $pdo->query("SELECT COUNT(*) FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = $ta_id WHERE ar.rombel_id = 77 AND ns.mapel_id = $mid AND ns.tahun_ajaran_id = $ta_id")->fetchColumn();
    $rCount = $pdo->query("SELECT COUNT(*) FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = $ta_id WHERE ar.rombel_id = 77 AND nr.mapel_id = $mid AND nr.tahun_ajaran_id = $ta_id")->fetchColumn();
    echo "- $mname (ID $mid): Formatif = $fCount | Sumatif = $sCount | Rapor = $rCount\n";
}

