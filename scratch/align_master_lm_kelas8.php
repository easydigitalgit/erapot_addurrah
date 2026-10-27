<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== MENYELARASKAN MASTER LM KELAS 8 GANJIL TENGAH ===\n";

$lmUpdates = [
    // 1. Bahasa Indonesia (Mapel ID: 3)
    [
        'mapel_id' => 3, 'kode_lm' => 'LM 1',
        'deskripsi' => 'menemukan data dan fakta pada teks laporan hasil observasi'
    ],
    [
        'mapel_id' => 3, 'kode_lm' => 'LM 2',
        'deskripsi' => 'menelaah penggunaan deskripsi dan eksposisi pada teks laporan hasil observasi'
    ],
    // 2. PJOK (Mapel ID: 9)
    [
        'mapel_id' => 9, 'kode_lm' => 'LM 1',
        'deskripsi' => 'Permainan bola besar, permainan bola kecil, atletik, dan bela diri.'
    ],
    [
        'mapel_id' => 9, 'kode_lm' => 'LM 2',
        'deskripsi' => 'Senam, gerak berirama, aktivitas air, kebugaran jasmani, serta pola hidup sehat dan keselamatan diri.'
    ],
    // 3. Seni Budaya (Mapel ID: 8)
    [
        'mapel_id' => 8, 'kode_lm' => 'LM 1',
        'deskripsi' => 'Seni rupa dan seni menggambar.'
    ],
    [
        'mapel_id' => 8, 'kode_lm' => 'LM 2',
        'deskripsi' => 'Seni musik, seni tari, dan seni teater.'
    ],
    // 4. PAIBP (Mapel ID: 1)
    [
        'mapel_id' => 1, 'kode_lm' => 'LM 1',
        'deskripsi' => 'Siswa mengetahui dan memahami nilai-nilai toleransi sebagaimana yang terkandung dalam Surah al-An\'am Ayat 108, Surah al-Kahf Ayat 29, Surah al-Kafirun Ayat 1-6, dan hadis tentang toleransi.'
    ],
    [
        'mapel_id' => 1, 'kode_lm' => 'LM 2',
        'deskripsi' => 'Mengimplementasikan pentingnya mengimani kitab-kitab Allah Swt. dan penerimanya sebagai wujud keimanan dan ketakwaan terhadap Tuhan Yang Maha Esa.'
    ],
    // 5. Hadits (Mapel ID: 11)
    [
        'mapel_id' => 11, 'kode_lm' => 'LM 1',
        'deskripsi' => 'Hadist ke 8'
    ],
    [
        'mapel_id' => 11, 'kode_lm' => 'LM 2',
        'deskripsi' => 'Hadist ke 9'
    ],
    // 6. Pendidikan Pancasila (Mapel ID: 2)
    [
        'mapel_id' => 2, 'kode_lm' => 'LM 1',
        'deskripsi' => 'Pancasila (memahami Pancasila sebagai dasar negara, pandangan hidup, dan ideologi negara)'
    ]
];

foreach ($lmUpdates as $u) {
    $mapelId = $u['mapel_id'];
    $kodeLm = $u['kode_lm'];
    $desc = $u['deskripsi'];
    
    $desc_a = "Menunjukkan penguasaan yang sangat baik dalam " . lcfirst(rtrim($desc, '.')) . ".";
    $desc_b = "Menunjukkan penguasaan yang baik dalam " . lcfirst(rtrim($desc, '.')) . ".";
    $desc_c = "Cukup menguasai dalam " . lcfirst(rtrim($desc, '.')) . ", namun perlu peningkatan pemahaman lebih lanjut.";
    $desc_d = "Perlu bimbingan dan pendampingan khusus dalam " . lcfirst(rtrim($desc, '.')) . ".";
    
    // Cek apakah LM sudah ada
    $check = $pdo->prepare("SELECT id FROM raporsmpit.master_lm WHERE mapel_id = ? AND tingkat = 8 AND semester = 'Ganjil' AND kategori = 'Tengah' AND kode_lm = ?");
    $check->execute([$mapelId, $kodeLm]);
    $exist = $check->fetch();
    
    if ($exist) {
        $upd = $pdo->prepare("UPDATE raporsmpit.master_lm SET deskripsi_lm = ?, deskripsi_a = ?, deskripsi_b = ?, deskripsi_c = ?, deskripsi_d = ?, status = 'Aktif' WHERE id = ?");
        $upd->execute([$desc, $desc_a, $desc_b, $desc_c, $desc_d, $exist['id']]);
        echo "UPDATE LM: Mapel ID $mapelId, $kodeLm -> '$desc'\n";
    } else {
        $ins = $pdo->prepare("INSERT INTO raporsmpit.master_lm (tahun_ajaran_id, mapel_id, tingkat, semester, kategori, kode_lm, deskripsi_lm, status, deskripsi_a, deskripsi_b, deskripsi_c, deskripsi_d) VALUES (10, ?, 8, 'Ganjil', 'Tengah', ?, ?, 'Aktif', ?, ?, ?, ?)");
        $ins->execute([$mapelId, $kodeLm, $desc, $desc_a, $desc_b, $desc_c, $desc_d]);
        echo "INSERT LM: Mapel ID $mapelId, $kodeLm -> '$desc'\n";
    }
}

$cnt = $pdo->query("SELECT COUNT(*) FROM raporsmpit.master_lm WHERE tingkat = 8 AND semester = 'Ganjil' AND kategori = 'Tengah'")->fetchColumn();
echo "\nTotal LM Tingkat 8 Ganjil Tengah di Database sekarang: $cnt LM\n";

