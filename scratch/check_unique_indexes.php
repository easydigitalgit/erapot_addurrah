<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');

// Check unique keys on siswa
$indexes = $m->query("SHOW INDEX FROM siswa WHERE Non_unique = 0");
echo "=== UNIQUE INDEXES ON SISWA ===\n";
while ($idx = $indexes->fetch_assoc()) {
    echo "Index: {$idx['Key_name']} on Column: {$idx['Column_name']}\n";
}
