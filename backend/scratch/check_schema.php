<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

echo "--- nilai_formatif schema ---\n";
$res = $mysqli->query("DESCRIBE nilai_formatif");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n--- nilai_sumatif schema ---\n";
$res = $mysqli->query("DESCRIBE nilai_sumatif");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

$mysqli->close();
