<?php
$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

$conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("DESCRIBE nilai_formatif");
while($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
