<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// 1. Cari Siswa Adabi Limansyah Sagala
$siswa = $pdo->query("SELECT * FROM siswa WHERE nama_lengkap LIKE '%Adabi Limansyah Sagala%'")->fetch(PDO::FETCH_ASSOC);
echo "Data Siswa:\n";
print_r($siswa);
$siswa_id = $siswa['id'];

// 2. Cari Anggota Rombel Adabi
$rombel = $pdo->query("SELECT ar.*, r.nama_rombel, r.tingkat FROM anggota_rombel ar JOIN rombel r ON r.id = ar.rombel_id WHERE ar.siswa_id = $siswa_id")->fetchAll(PDO::FETCH_ASSOC);
echo "\nRombel Siswa:\n";
print_r($rombel);

// 3. Cari Nilai Formatif Adabi di TA 10
echo "\nNilai Formatif (TA 10):\n";
$formatif = $pdo->query("
    SELECT nf.mapel_id, m.nama_mapel, nf.kategori, nf.jenis_penilaian, nf.pertemuan, nf.nilai_angka, nf.catatan
    FROM nilai_formatif nf
    JOIN mata_pelajaran m ON m.id = nf.mapel_id
    WHERE nf.siswa_id = $siswa_id AND nf.tahun_ajaran_id = 10
    ORDER BY nf.mapel_id, nf.pertemuan
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($formatif as $f) {
    echo "- [Mapel {$f['mapel_id']} - {$f['nama_mapel']}] P{$f['pertemuan']} {$f['jenis_penilaian']} ({$f['kategori']}): {$f['nilai_angka']}\n";
}

// 4. Cari Nilai Sumatif Adabi di TA 10
echo "\nNilai Sumatif (TA 10):\n";
$sumatif = $pdo->query("
    SELECT ns.mapel_id, m.nama_mapel, ns.kategori, ns.jenis_sumatif, ns.nilai, ns.deskripsi
    FROM nilai_sumatif ns
    JOIN mata_pelajaran m ON m.id = ns.mapel_id
    WHERE ns.siswa_id = $siswa_id AND ns.tahun_ajaran_id = 10
    ORDER BY ns.mapel_id
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($sumatif as $s) {
    echo "- [Mapel {$s['mapel_id']} - {$s['nama_mapel']}] {$s['jenis_sumatif']} ({$s['kategori']}): {$s['nilai']}\n";
}

// 5. Cari Nilai Rapor Adabi di TA 10
echo "\nNilai Rapor (TA 10):\n";
$rapor = $pdo->query("
    SELECT nr.*, m.nama_mapel
    FROM nilai_rapor nr
    JOIN mata_pelajaran m ON m.id = nr.mapel_id
    WHERE nr.siswa_id = $siswa_id AND nr.tahun_ajaran_id = 10
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rapor as $r) {
    echo "- [Mapel {$r['mapel_id']} - {$r['nama_mapel']}] ({$r['kategori']}): NA = {$r['nilai_akhir']}, Rata Formatif = {$r['rata_formatif']}, Rata Sumatif = {$r['rata_sumatif']}, Deskripsi = {$r['deskripsi']}\n";
}
