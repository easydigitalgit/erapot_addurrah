<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}", $db_config['user'], $db_config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "--- SETTING BOBOT NILAI ---\n";
    $stmt = $pdo->query("SELECT * FROM setting_bobot_nilai");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\n--- NILAI SISWA (AISYAHARA INDRI) ---\n";
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $stmt->execute(['0117905743']);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($siswa) {
        $siswa_id = $siswa['id'];
        echo "Siswa ID: $siswa_id\n";
        
        echo "\nSUMATIF:\n";
        $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = 45"); // Mapel 45 = Bahasa Indonesia (Assuming from context or just checking all)
        // Wait, I don't know the mapel_id for Bahasa Indonesia. Let's find it first.
        $stmt_m = $pdo->prepare("SELECT id FROM mata_pelajaran WHERE nama_mapel LIKE '%Bahasa Indonesia%'");
        $stmt_m->execute();
        $mapel = $stmt_m->fetch(PDO::FETCH_ASSOC);
        $mapel_id = $mapel['id'] ?? 0;
        echo "Mapel ID (Bahasa Indonesia): $mapel_id\n";
        
        $stmt = $pdo->prepare("SELECT * FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ?");
        $stmt->execute([$siswa_id, $mapel_id]);
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        
        echo "\nFORMATIF:\n";
        $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE siswa_id = ? AND mapel_id = ?");
        $stmt->execute([$siswa_id, $mapel_id]);
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo "Siswa tidak ditemukan.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
