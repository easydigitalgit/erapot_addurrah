<?php
// scratch/dump_structure.php
require 'vendor/autoload.php';
// Or just manual mysqli if I know the credentials from .env
// Let's look at .env first to get credentials
$host = 'localhost';
$user = 'dev';
$pass = '12345678';
$name = 'raporsmpit';

$conn = new mysqli($host, $user, $pass, $name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$tables = ['setoran_tahfidz', 'ref_juz', 'ref_surah'];
foreach ($tables as $table) {
    echo "\n--- Structure of $table ---\n";
    $result = $conn->query("DESCRIBE $table");
    while($row = $result->fetch_assoc()) {
        printf("%-20s %-15s %-5s %-5s %-10s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default']);
    }
}
$conn->close();
