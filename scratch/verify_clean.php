<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "Remaining in siswa: " . $mysqli->query("SELECT COUNT(*) FROM siswa WHERE id BETWEEN 581 AND 620")->fetch_row()[0] . "\n";
echo "Remaining in orangtua_wali: " . $mysqli->query("SELECT COUNT(*) FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620")->fetch_row()[0] . "\n";
echo "Total siswa in database: " . $mysqli->query("SELECT COUNT(*) FROM siswa")->fetch_row()[0] . "\n";
