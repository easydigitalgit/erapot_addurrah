<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=raporsmpit;charset=utf8mb4", "root", "");

$names = ['EMERY ALDRIC', 'Nadhif', 'NASRUL HAMZAH'];
foreach ($names as $name) {
    $stmt = $pdo->prepare("SELECT id, nis, nama_lengkap, rombel_id FROM siswa WHERE nama_lengkap LIKE ?");
    $stmt->execute(["%$name%"]);
    echo "Search '$name':\n";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
