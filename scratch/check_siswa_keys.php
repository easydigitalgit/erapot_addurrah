<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$indexes = $pdo->query('SHOW INDEX FROM siswa')->fetchAll(PDO::FETCH_ASSOC);
foreach($indexes as $idx) {
    if (!$idx['Non_unique']) {
        echo 'UNIQUE KEY: ' . $idx['Key_name'] . ' -> ' . $idx['Column_name'] . PHP_EOL;
    }
}
