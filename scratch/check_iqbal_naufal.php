<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

foreach ([47 => 'IQBAL AR ZAAKY', 55 => 'NAUFAL AL-KHALISH', 427 => 'MUHAMMAD AFKHAR AYUB'] as $id => $name) {
    echo "=== Siswa ID $id: $name ===\n";
    $s = $pdo->query("SELECT id, nis, nisn, nama_lengkap, status_siswa FROM siswa WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    print_r($s);

    $f = $pdo->query("SELECT m.nama_mapel, COUNT(*) as c FROM nilai_formatif nf JOIN mata_pelajaran m ON m.id = nf.mapel_id WHERE nf.siswa_id = $id AND nf.tahun_ajaran_id = 10 GROUP BY m.nama_mapel")->fetchAll(PDO::FETCH_ASSOC);
    echo "Formatif di TA 10:\n";
    foreach ($f as $row) echo "  - {$row['nama_mapel']}: {$row['c']} baris\n";

    $sm = $pdo->query("SELECT m.nama_mapel, ns.nilai FROM nilai_sumatif ns JOIN mata_pelajaran m ON m.id = ns.mapel_id WHERE ns.siswa_id = $id AND ns.tahun_ajaran_id = 10")->fetchAll(PDO::FETCH_ASSOC);
    echo "Sumatif di TA 10:\n";
    foreach ($sm as $row) echo "  - {$row['nama_mapel']}: {$row['nilai']}\n";
}

