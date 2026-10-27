<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    if (strpos($t, 'lm') !== false || strpos($t, 'materi') !== false || strpos($t, 'tujuan') !== false || strpos($t, 'kompetensi') !== false || strpos($t, 'kd') !== false) {
        echo $t . PHP_EOL;
    }
}

