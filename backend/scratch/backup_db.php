<?php
// scratch/backup_db.php
$host = 'localhost';
$user = 'dev';
$pass = '12345678';
$name = 'raporsmpit';

$conn = new mysqli($host, $user, $pass, $name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$timestamp = date('Ymd_His');
$backupTable = "setoran_tahfidz_backup_$timestamp";

$sql = "CREATE TABLE $backupTable AS SELECT * FROM setoran_tahfidz";
if ($conn->query($sql) === TRUE) {
    echo "Database table 'setoran_tahfidz' backed up to '$backupTable' successfully.";
} else {
    echo "Error backing up database table: " . $conn->error;
}
$conn->close();
