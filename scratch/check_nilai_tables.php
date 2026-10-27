<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$stmt = $pdo->query("SHOW TABLES LIKE '%nilai%'");
while($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo $row[0] . PHP_EOL;
}

