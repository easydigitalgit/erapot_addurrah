<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

// Ambil nilai Adabi dari Excel PAIBP Topaz:
// NH1=60, NH2=60 -> avg_nh=60
// UH1=60, UH2=60 -> avg_uh=60
// STS=55
// NR = (55*0.3) + (60*0.35) + (60*0.35) = 16.5 + 21 + 21 = 58.5 (atau jika UH O10=75 maka 63.8)
// Mari kita cek apakah kita simpan nilai_rapor untuk Adabi di mapel 1:

$data = [
    'siswa_id' => 33,
    'rombel_id' => 73,
    'tahun_ajaran_id' => 10,
    'mapel_id' => 1,
    'kategori' => 'Tengah Semester',
    'rata_formatif' => 60,
    'rata_sumatif' => 55,
    'nilai_akhir' => 64.0,
    'predikat' => 'Kurang',
    'deskripsi' => ''
];

// Cek apakah sudah ada
$stmt = $pdo->prepare("SELECT * FROM nilai_rapor WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ?");
$stmt->execute([33, 1, 10, 'Tengah Semester']);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo "Sudah ada record ID: {$existing['id']}\n";
} else {
    echo "Belum ada record di nilai_rapor untuk Adabi di PAIBP!\n";
}

