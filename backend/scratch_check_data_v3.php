<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=raporsmpit", "dev", "12345678");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "CHECKING MASTER LM:\n";
    $stmt = $pdo->prepare("SELECT * FROM master_lm WHERE mapel_id = 6 AND tingkat IN ('7', 'VII') AND semester = 'Genap' AND kategori = 'Tengah'");
    $stmt->execute();
    $lms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lms as $lm) {
        echo json_encode($lm) . "\n";
    }

    echo "\nCHECKING NILAI FORMATIF SAMPLE (Rombel 16, Mapel 6, TA 9, Sem Genap):\n";
    $stmt = $pdo->prepare("SELECT * FROM nilai_formatif WHERE rombel_id = 16 AND mapel_id = 6 AND tahun_ajaran_id = 9 AND semester = 'Genap' LIMIT 5");
    $stmt->execute();
    $nilais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($nilais as $n) {
        echo json_encode($n) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
