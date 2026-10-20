<?php
/**
 * Script Penyesuaian Index Tabel orangtua_wali
 * Mengubah index user_id dan email_ortu dari UNIQUE menjadi NON-UNIQUE (KEY biasa),
 * sehingga satu orang tua (user_id yang sama) dapat memiliki lebih dari satu anak (kakak-beradik).
 * 
 * Script ini aman dijalankan berulang kali (idempotent) di Local maupun Production/VPS.
 */

$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    $envPath = dirname(__DIR__) . '/.env';
}

$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'raporsmpit',
    'port' => 3306
];

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (empty($trimmed) || strpos($trimmed, '#') === 0) continue;
        
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim(trim($parts[1]), "\"'");
            
            if ($key === 'database.default.hostname' || $key === 'DB_HOST') $dbConfig['host'] = $val;
            if ($key === 'database.default.username' || $key === 'DB_USER') $dbConfig['user'] = $val;
            if ($key === 'database.default.password' || $key === 'DB_PASS') $dbConfig['pass'] = $val;
            if ($key === 'database.default.database' || $key === 'DB_NAME') $dbConfig['name'] = $val;
            if ($key === 'database.default.port' || $key === 'DB_PORT') $dbConfig['port'] = (int)$val;
        }
    }
}

echo "========================================================\n";
echo " SCRIPT PERBAIKAN INDEX TABEL orangtua_wali\n";
echo "========================================================\n";
echo "Host:     {$dbConfig['host']}:{$dbConfig['port']}\n";
echo "Database: {$dbConfig['name']}\n";
echo "User:     {$dbConfig['user']}\n";
echo "--------------------------------------------------------\n";

mysqli_report(MYSQLI_REPORT_OFF);

$c = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name'], $dbConfig['port']);
if (!$c) {
    die("ERROR: Gagal terkoneksi ke database: " . mysqli_connect_error() . "\n");
}

function getIndexes($conn) {
    $indexes = [];
    $res = $conn->query("SHOW INDEXES FROM orangtua_wali");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $indexes[] = $r;
        }
    }
    return $indexes;
}

echo "1. Memeriksa index yang ada saat ini:\n";
$current = getIndexes($c);
$hasNonUniqueUserId = false;
$uniqueIndexesToDrop = [];

foreach ($current as $idx) {
    $kName = $idx['Key_name'];
    $col = $idx['Column_name'];
    $isUnique = ($idx['Non_unique'] == 0);
    echo "   - {$kName} (kolom: {$col}, " . ($isUnique ? "UNIQUE" : "NON-UNIQUE") . ")\n";

    if ($col === 'user_id' && !$isUnique) {
        $hasNonUniqueUserId = true;
    }

    if ($isUnique && $kName !== 'PRIMARY') {
        if ($col === 'user_id' || $col === 'email_ortu') {
            $uniqueIndexesToDrop[$kName] = $col;
        }
    }
}

// Langkah 1: Pastikan index non-unique untuk user_id ada terlebih dahulu
// agar Foreign Key Constraint orangtua_wali_ibfk_1 tetap terpenuhi saat Unique Index di-drop
if (!$hasNonUniqueUserId) {
    echo "\n2. Menambahkan index non-unique 'idx_orangtua_wali_user_id'...\n";
    if ($c->query("ALTER TABLE orangtua_wali ADD INDEX idx_orangtua_wali_user_id (user_id)")) {
        echo "   [SUKSES] Index non-unique 'idx_orangtua_wali_user_id' berhasil dibuat.\n";
    } else {
        echo "   [INFO/WARN] " . $c->error . "\n";
    }
} else {
    echo "\n2. Index non-unique untuk 'user_id' sudah ada. (OK)\n";
}

// Langkah 2: Drop semua unique index pada user_id dan email_ortu
if (!empty($uniqueIndexesToDrop)) {
    echo "\n3. Menghapus Unique Index yang membatasi relasi siswa kakak-beradik:\n";
    foreach ($uniqueIndexesToDrop as $kName => $col) {
        echo "   -> Menghapus UNIQUE INDEX '{$kName}' (kolom: {$col})...\n";
        if ($c->query("ALTER TABLE orangtua_wali DROP INDEX `{$kName}`")) {
            echo "      [SUKSES] UNIQUE INDEX '{$kName}' berhasil dihapus.\n";
        } else {
            echo "      [GAGAL] " . $c->error . "\n";
        }
    }
} else {
    echo "\n3. Tidak ada UNIQUE INDEX bermasalah pada 'user_id' atau 'email_ortu'. (Sudah aman)\n";
}

// Langkah 3: Verifikasi index final
echo "\n4. Daftar index final pada tabel orangtua_wali:\n";
$finalIndexes = getIndexes($c);
$isCompletelySafe = true;
foreach ($finalIndexes as $idx) {
    $kName = $idx['Key_name'];
    $col = $idx['Column_name'];
    $isUnique = ($idx['Non_unique'] == 0);
    echo "   - {$kName} (kolom: {$col}, " . ($isUnique ? "UNIQUE" : "NON-UNIQUE") . ")\n";
    if ($isUnique && $kName !== 'PRIMARY' && ($col === 'user_id' || $col === 'email_ortu')) {
        $isCompletelySafe = false;
    }
}

echo "--------------------------------------------------------\n";
if ($isCompletelySafe) {
    echo "[STATUS: SUKSES] Tabel orangtua_wali kini dapat menerima data siswa dengan orang tua yang sama (kakak-beradik) tanpa error Duplicate Entry.\n";
} else {
    echo "[STATUS: PERINGATAN] Masih ada unique index yang tersisa. Silakan periksa detail di atas.\n";
}
echo "========================================================\n";
