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

    echo "Starting GLOBAL Grade Cleanup...\n";

    // 1. Identifikasi semua baris yang memiliki kategori kosong/null
    $sql = "SELECT * FROM nilai_formatif WHERE kategori = '' OR kategori IS NULL";
    $stmt = $pdo->query($sql);
    $orphans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($orphans) . " records with empty categories.\n";

    $merged = 0;
    $deleted = 0;
    $updated = 0;

    $pdo->beginTransaction();

    foreach ($orphans as $row) {
        // Cari pasangan yang sudah ada kategorinya
        $sqlCek = "SELECT id, kategori, catatan FROM nilai_formatif 
                   WHERE siswa_id = ? AND mapel_id = ? AND rombel_id = ? 
                   AND jenis_penilaian = ? AND pertemuan = ? 
                   AND tahun_ajaran_id = ? AND semester = ?
                   AND kategori != '' AND kategori IS NOT NULL
                   LIMIT 1";
        
        $stmtCek = $pdo->prepare($sqlCek);
        $stmtCek->execute([
            $row['siswa_id'], $row['mapel_id'], $row['rombel_id'],
            $row['jenis_penilaian'], $row['pertemuan'],
            $row['tahun_ajaran_id'], $row['semester']
        ]);
        
        $existing = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Merging catatan jika yang eksis masih kosong
            if (empty($existing['catatan']) && !empty($row['catatan'])) {
                $upd = $pdo->prepare("UPDATE nilai_formatif SET catatan = ? WHERE id = ?");
                $upd->execute([$row['catatan'], $existing['id']]);
                $merged++;
            }
            
            // Hapus yang kosong karena sudah ada pasangannya
            $del = $pdo->prepare("DELETE FROM nilai_formatif WHERE id = ?");
            $del->execute([$row['id']]);
            $deleted++;
        } else {
            // Jika tidak ada pasangannya, berikan kategori default 'Tengah Semester' 
            // agar data tidak hilang dari kalkulasi
            $upd = $pdo->prepare("UPDATE nilai_formatif SET kategori = 'Tengah Semester' WHERE id = ?");
            $upd->execute([$row['id']]);
            $updated++;
        }
    }

    $pdo->commit();

    echo "\nCleanup Summary:\n";
    echo "- Records Deleted (Duplicates): $deleted\n";
    echo "- Records Merged (Notes Transferred): $merged\n";
    echo "- Records Updated to 'Tengah Semester' (Orphans): $updated\n";
    echo "GLOBAL Cleanup Complete.\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
