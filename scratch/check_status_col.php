<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$cols = $pdo->query("DESCRIBE siswa")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    if ($c['Field'] === 'status_siswa' || $c['Field'] === 'nis') {
        echo "{$c['Field']}: {$c['Type']}\n";
    }
}
$statuses = $pdo->query("SELECT DISTINCT status_siswa FROM siswa")->fetchAll(PDO::FETCH_COLUMN);
echo "Nilai status_siswa di DB: " . implode(', ', $statuses) . "\n";

