<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== Columns in rombel ===\n";
foreach ($pdo->query("DESCRIBE rombel")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "  {$col['Field']} ({$col['Type']})\n";
}

echo "=== Columns in anggota_rombel ===\n";
foreach ($pdo->query("DESCRIBE anggota_rombel")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "  {$col['Field']} ({$col['Type']})\n";
}

echo "=== Columns in nilai_formatif ===\n";
foreach ($pdo->query("DESCRIBE nilai_formatif")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "  {$col['Field']} ({$col['Type']})\n";
}

echo "=== Columns in nilai_sumatif ===\n";
foreach ($pdo->query("DESCRIBE nilai_sumatif")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "  {$col['Field']} ({$col['Type']})\n";
}

echo "=== Columns in master_lm ===\n";
foreach ($pdo->query("DESCRIBE master_lm")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "  {$col['Field']} ({$col['Type']})\n";
}
