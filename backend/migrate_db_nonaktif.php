<?php
/**
 * Database Migration Helper Script for VPS Deployment
 * This script will create the `mapel_nonaktif_rombel` table using PDO
 * by reading database credentials from the local `.env` file automatically.
 */

$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    $envPath = dirname(__DIR__) . '/.env';
}

// Default fallback configuration
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'raporsmpit'
];

// Parse .env if it exists
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (empty($trimmed) || strpos($trimmed, '#') === 0) {
            continue;
        }
        
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim(trim($parts[1]), "\"'");
            
            // Map common CodeIgniter 4 environment variables
            if ($key === 'database.default.hostname' || $key === 'DB_HOST') {
                $dbConfig['host'] = $val;
            }
            if ($key === 'database.default.username' || $key === 'DB_USER') {
                $dbConfig['user'] = $val;
            }
            if ($key === 'database.default.password' || $key === 'DB_PASS') {
                $dbConfig['pass'] = $val;
            }
            if ($key === 'database.default.database' || $key === 'DB_NAME') {
                $dbConfig['name'] = $val;
            }
        }
    }
}

echo "==========================================\n";
echo " DATABASE MIGRATION SCRIPT\n";
echo "==========================================\n";
echo "Database Configuration Loaded:\n";
echo "- Host:     " . $dbConfig['host'] . "\n";
echo "- User:     " . $dbConfig['user'] . "\n";
echo "- Database: " . $dbConfig['name'] . "\n";
echo "------------------------------------------\n";

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS `mapel_nonaktif_rombel` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `mapel_id` INT NOT NULL,
        `rombel_id` INT NOT NULL,
        `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        KEY `mapel_id` (`mapel_id`),
        KEY `rombel_id` (`rombel_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    echo "Executing SQL query...\n";
    $pdo->exec($sql);
    
    echo "\nSUCCESS: Table 'mapel_nonaktif_rombel' created successfully!\n";
    echo "==========================================\n";
} catch (PDOException $e) {
    echo "\nERROR: Database Migration Failed!\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "==========================================\n";
    exit(1);
}
