<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=raporsmpit", "dev", "12345678");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "CHECKING kategori in nilai_formatif for Rombel 16, Mapel 6, TA 9:\n";
    $stmt = $pdo->prepare("SELECT kategori, COUNT(*) as total FROM nilai_formatif WHERE rombel_id = 16 AND mapel_id = 6 AND tahun_ajaran_id = 9 GROUP BY kategori");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo json_encode($row) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
