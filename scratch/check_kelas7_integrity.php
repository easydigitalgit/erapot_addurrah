<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// 1. Cek duplikasi di nilai_formatif
$q1 = $pdo->query('SELECT tujuan_pembelajaran_id, siswa_id, COUNT(*) as c FROM nilai_formatif GROUP BY tujuan_pembelajaran_id, siswa_id HAVING c > 1')->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikat record di nilai_formatif: " . count($q1) . PHP_EOL;

// 2. Cek duplikasi di nilai_sumatif
$q2 = $pdo->query('SELECT lingkup_materi_id, siswa_id, jenis_penilaian, COUNT(*) as c FROM nilai_sumatif GROUP BY lingkup_materi_id, siswa_id, jenis_penilaian HAVING c > 1')->fetchAll(PDO::FETCH_ASSOC);
echo "Duplikat record di nilai_sumatif: " . count($q2) . PHP_EOL;

// 3. Cek anggota_rombel kelas VII saat ini
$q3 = $pdo->query("SELECT r.nama_rombel, COUNT(ar.siswa_id) as total_siswa, COUNT(DISTINCT ar.siswa_id) as distinct_siswa 
                   FROM rombel r 
                   LEFT JOIN anggota_rombel ar ON ar.rombel_id = r.id 
                   WHERE r.tingkat = 'VII' 
                   GROUP BY r.id, r.nama_rombel")->fetchAll(PDO::FETCH_ASSOC);

echo PHP_EOL . "Status Rombel Kelas VII saat ini:" . PHP_EOL;
foreach ($q3 as $row) {
    echo "- " . str_pad($row['nama_rombel'], 15) . ": Total Anggota = {$row['total_siswa']}, Unik = {$row['distinct_siswa']}" . PHP_EOL;
}

// 4. Cek sampel siswa kelas VII dan nilai mereka
$q4 = $pdo->query("SELECT s.id, s.nama_lengkap, r.nama_rombel, 
                          (SELECT COUNT(*) FROM nilai_formatif nf WHERE nf.siswa_id = s.id) as jml_formatif,
                          (SELECT COUNT(*) FROM nilai_sumatif ns WHERE ns.siswa_id = s.id) as jml_sumatif
                   FROM siswa s 
                   JOIN anggota_rombel ar ON ar.siswa_id = s.id 
                   JOIN rombel r ON r.id = ar.rombel_id 
                   WHERE r.tingkat = 'VII' 
                   LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

echo PHP_EOL . "Sampel Integritas Nilai Siswa Kelas VII:" . PHP_EOL;
foreach ($q4 as $s) {
    echo "- [ID: {$s['id']}] {$s['nama_lengkap']} ({$s['nama_rombel']}) -> Formatif: {$s['jml_formatif']}, Sumatif: {$s['jml_sumatif']}" . PHP_EOL;
}

