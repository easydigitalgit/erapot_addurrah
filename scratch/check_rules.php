<?php
// require_once 'vendor/autoload.php';
// Use the CI4 environment if possible or just raw PDO
// Since I'm in the xampp/htdocs/erapoteasy directory, I should check the .env or database config

$db_host = 'localhost';
$db_user = 'root'; // Try root first, if not then raporsmpit
$db_pass = '';
$db_name = 'raporsmpit'; 

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "--- setting_aturan_nilai ---\n";
    $stmt = $pdo->query("SELECT * FROM setting_aturan_nilai ORDER BY nilai_max DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    echo "\n--- setting_bobot_nilai ---\n";
    $stmt = $pdo->query("SELECT * FROM setting_bobot_nilai");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
