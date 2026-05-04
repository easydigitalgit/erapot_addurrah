<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=raporsmpit", "dev", "12345678");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE '%Granit%'");
    $stmt->execute();
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "ROMBEL: " . json_encode($rombel) . "\n";

    $stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE nama_mapel LIKE '%Inggris%'");
    $stmt->execute();
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "MAPEL: " . json_encode($mapel) . "\n";

    $stmt = $pdo->prepare("SELECT * FROM tahun_ajaran WHERE status = 'Aktif'");
    $stmt->execute();
    $ta = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "TA AKTIF: " . json_encode($ta) . "\n";

    if ($rombel && $ta) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM anggota_rombel WHERE rombel_id = ? AND tahun_ajaran_id = ? AND semester = ?");
        $stmt->execute([$rombel['id'], $ta['id'], $ta['semester']]);
        echo "STUDENTS IN anggota_rombel (ACTIVE TA/SEM): " . $stmt->fetchColumn() . "\n";
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM anggota_rombel WHERE rombel_id = ? AND tahun_ajaran_id = ?");
        $stmt->execute([$rombel['id'], $ta['id']]);
        echo "STUDENTS IN anggota_rombel (ACTIVE TA ONLY): " . $stmt->fetchColumn() . "\n";

        if ($mapel) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM nilai_formatif WHERE rombel_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND semester = ?");
            $stmt->execute([$rombel['id'], $mapel['id'], $ta['id'], $ta['semester']]);
            echo "GRADES IN nilai_formatif: " . $stmt->fetchColumn() . "\n";
            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM nilai_sumatif WHERE mapel_id = ? AND tahun_ajaran_id = ?");
            $stmt->execute([$mapel['id'], $ta['id']]);
            echo "GRADES IN nilai_sumatif: " . $stmt->fetchColumn() . "\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
