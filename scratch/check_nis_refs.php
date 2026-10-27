<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$res = $pdo->query("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'raporsmpit' AND COLUMN_NAME = 'nis'")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

