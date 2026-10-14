<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$res = $pdo->query("SELECT * FROM rombel WHERE id IN (52, 53, 54, 55, 56, 77, 78, 79, 80, 81)")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
