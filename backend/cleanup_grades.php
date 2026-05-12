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

    // Find Rombel ID for "Granit"
    $stmt = $pdo->prepare("SELECT id FROM rombel WHERE nama_rombel LIKE '%Granit%'");
    $stmt->execute();
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    $rombel_id = $rombel['id'];

    // Find Mapel ID for "Bahasa Indonesia"
    $stmt = $pdo->prepare("SELECT id FROM mata_pelajaran WHERE nama_mapel LIKE '%Bahasa Indonesia%'");
    $stmt->execute();
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);
    $mapel_id = $mapel['id'];

    echo "Cleaning up Granit (ID: $rombel_id) - Bahasa Indonesia (ID: $mapel_id)\n";

    // 1. Find all pairs of (siswa, jenis, pert) where one has category '' and another has 'Tengah Semester'
    $sql = "SELECT t1.id as id_empty, t2.id as id_filled, t1.catatan as catatan_empty, t2.catatan as catatan_filled
            FROM nilai_formatif t1
            JOIN nilai_formatif t2 ON t1.siswa_id = t2.siswa_id 
                AND t1.mapel_id = t2.mapel_id 
                AND t1.rombel_id = t2.rombel_id 
                AND t1.jenis_penilaian = t2.jenis_penilaian 
                AND t1.pertemuan = t2.pertemuan
                AND t1.tahun_ajaran_id = t2.tahun_ajaran_id
                AND t1.semester = t2.semester
            WHERE t1.rombel_id = ? AND t1.mapel_id = ?
                AND t1.kategori = ''
                AND t2.kategori = 'Tengah Semester'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rombel_id, $mapel_id]);
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($duplicates) . " duplicate pairs.\n";

    $pdo->beginTransaction();
    foreach ($duplicates as $row) {
        $id_empty = $row['id_empty'];
        $id_filled = $row['id_filled'];
        
        // If the filled one has no catatan but the empty one does, transfer it.
        if (empty($row['catatan_filled']) && !empty($row['catatan_empty'])) {
            $update = $pdo->prepare("UPDATE nilai_formatif SET catatan = ? WHERE id = ?");
            $update->execute([$row['catatan_empty'], $id_filled]);
            echo "Transferred catatan from ID $id_empty to ID $id_filled\n";
        }
        
        // Delete the empty category one
        $delete = $pdo->prepare("DELETE FROM nilai_formatif WHERE id = ?");
        $delete->execute([$id_empty]);
        echo "Deleted duplicate ID $id_empty\n";
    }
    
    // 2. Also handle those that ONLY have '' category and no 'Tengah Semester' counterpart
    // We should probably just update them to 'Tengah Semester' so they are consistent
    $sql = "UPDATE nilai_formatif SET kategori = 'Tengah Semester' 
            WHERE rombel_id = ? AND mapel_id = ? AND kategori = ''";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rombel_id, $mapel_id]);
    echo "Updated " . $stmt->rowCount() . " remaining empty category records to 'Tengah Semester'.\n";

    $pdo->commit();
    echo "Cleanup complete.\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
