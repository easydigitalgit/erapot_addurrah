<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$ta_id = 10;
$kategori = 'Tengah Semester';
$semester = 'Ganjil';

// Ambil bobot
$bobotRows = $pdo->query("SELECT * FROM setting_bobot_nilai WHERE kategori = 'tengah_semester'")->fetchAll(PDO::FETCH_ASSOC);
$bobot = ['nh' => 0.35, 'uh' => 0.35, 'sts' => 0.30];
foreach ($bobotRows as $b) {
    if (isset($bobot[$b['sub_kategori']])) {
        $bobot[$b['sub_kategori']] = ((float)$b['bobot']) / 100;
    }
}

// Ambil aturan predikat
$aturanPredikat = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC")->fetchAll(PDO::FETCH_ASSOC);

// Cari semua kombinasi (siswa, mapel) di TA 10 yang punya formatif atau sumatif
$sql = "
    SELECT s.id as siswa_id, s.nama_lengkap, ar.rombel_id, r.nama_rombel, r.tingkat, m.id as mapel_id, m.nama_mapel
    FROM siswa s
    JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = $ta_id
    JOIN rombel r ON r.id = ar.rombel_id
    CROSS JOIN mata_pelajaran m
    WHERE m.status = 'Aktif'
      AND (
          EXISTS (SELECT 1 FROM nilai_formatif nf WHERE nf.siswa_id = s.id AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = $ta_id AND nf.kategori = '$kategori')
          OR
          EXISTS (SELECT 1 FROM nilai_sumatif ns WHERE ns.siswa_id = s.id AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = $ta_id AND ns.kategori = '$kategori')
      )
      AND NOT EXISTS (
          SELECT 1 FROM nilai_rapor nr WHERE nr.siswa_id = s.id AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = $ta_id AND nr.kategori = '$kategori'
      )
    ORDER BY r.tingkat, r.nama_rombel, s.nama_lengkap, m.id
";

$missingList = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "Total siswa-mapel yang punya nilai mentah tapi BELUM ada di nilai_rapor: " . count($missingList) . PHP_EOL;

$grouped = [];
foreach ($missingList as $item) {
    $key = "{$item['tingkat']} - {$item['nama_rombel']} | Mapel: {$item['nama_mapel']}";
    if (!isset($grouped[$key])) $grouped[$key] = 0;
    $grouped[$key]++;
}

foreach ($grouped as $k => $c) {
    echo "- $k : $c siswa\n";
}
