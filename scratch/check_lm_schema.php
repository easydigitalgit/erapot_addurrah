<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$stmt = $pdo->query("DESCRIBE lingkup_materi");
while($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}

