<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=raporsmpit", "dev", "12345678");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $affected = $pdo->exec("UPDATE nilai_formatif SET kategori = 'Tengah Semester' WHERE kategori = '' OR kategori = 'Tengah'");
    echo "Successfully updated $affected records.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
