<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $stmt = $pdo->query("SHOW DATABASES");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Database'] . "\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
