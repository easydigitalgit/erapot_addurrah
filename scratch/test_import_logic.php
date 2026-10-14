<?php
require __DIR__ . '/../backend/vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$stmt = $pdo->query('SELECT id, nama_rombel, tingkat, id_tahun_ajaran, semester FROM rombel ORDER BY id_tahun_ajaran DESC, id ASC');
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($r) . "\n";
}
