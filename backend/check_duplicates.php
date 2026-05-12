<?php
// Simple PDO check
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Find Rombel ID for "Granit"
    $stmt = $pdo->prepare("SELECT id FROM rombel WHERE nama_rombel LIKE '%Granit%'");
    $stmt->execute();
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    $rombel_id = $rombel ? $rombel['id'] : null;

    // Find Mapel ID for "Bahasa Indonesia"
    $stmt = $pdo->prepare("SELECT id FROM mata_pelajaran WHERE nama_mapel LIKE '%Bahasa Indonesia%'");
    $stmt->execute();
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);
    $mapel_id = $mapel ? $mapel['id'] : null;

    echo "Rombel ID: $rombel_id, Mapel ID: $mapel_id\n";

    if ($rombel_id && $mapel_id) {
        // Detailed check for specific students from screenshot
        // Ahmad Oka Al Fatih (NISN: 3138711444)
        // Ahya Maulidan (NISN: 3131980647)
        // Akil Zaydan Rahmana (NISN: 3136195157)
        // Arga Reyhan Bahari (NISN: 3128614911)

        $siswas = [
            '3138711444' => 'Ahmad Oka Al Fatih',
            '3131980647' => 'Ahya Maulidan',
            '3136195157' => 'Akil Zaydan Rahmana',
            '3128614911' => 'Arga Reyhan Bahari'
        ];

        foreach ($siswas as $nisn => $nama) {
            echo "\n--- $nama ($nisn) ---\n";
            $stmt = $pdo->prepare("SELECT s.id FROM siswa s WHERE s.nisn = ? OR s.nama_lengkap LIKE ?");
            $stmt->execute([$nisn, "%$nama%"]);
            $s = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($s) {
                $siswa_id = $s['id'];
                $sql = "SELECT id, jenis_penilaian, pertemuan, kategori, nilai_angka 
                        FROM nilai_formatif 
                        WHERE siswa_id = ? AND mapel_id = ? AND rombel_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$siswa_id, $mapel_id, $rombel_id]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    echo "ID: {$row['id']}, Jenis: {$row['jenis_penilaian']}, Pert: {$row['pertemuan']}, Kat: '{$row['kategori']}', Nilai: {$row['nilai_angka']}\n";
                }
            } else {
                echo "Siswa not found.\n";
            }
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
