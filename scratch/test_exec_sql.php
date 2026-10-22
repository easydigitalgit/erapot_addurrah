<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');

try {
    $pdo->exec($sql);
    echo "SUCCESS: Query perbaikan_semua_kelas_dapodik_2026_2027.sql berhasil dieksekusi tanpa error sama sekali!\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
