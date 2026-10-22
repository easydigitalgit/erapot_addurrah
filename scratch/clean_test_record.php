<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'dev', '12345678');
$pdo->exec("DELETE FROM master_lm WHERE id = 354");
echo "Deleted test row 354 successfully.\n";
