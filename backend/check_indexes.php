<?php
/**
 * Diagnostic tool to check index status and test parent user duplication
 */
$envPath = __DIR__ . '/.env';
$dbConfig = ['host' => 'localhost', 'user' => 'dev', 'pass' => '12345678', 'name' => 'raporsmpit', 'port' => 3306];

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $trimmed = trim($line);
        if (empty($trimmed) || strpos($trimmed, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $k = trim($parts[0]);
            $v = trim(trim($parts[1]), "\"'");
            if ($k === 'database.default.hostname') $dbConfig['host'] = $v;
            if ($k === 'database.default.username') $dbConfig['user'] = $v;
            if ($k === 'database.default.password') $dbConfig['pass'] = $v;
            if ($k === 'database.default.database') $dbConfig['name'] = $v;
            if ($k === 'database.default.port') $dbConfig['port'] = (int)$v;
        }
    }
}

$c = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name'], $dbConfig['port']);
if (!$c) die("Connection error: " . mysqli_connect_error());

echo "=== DIAGNOSTIC REPORT: orangtua_wali ===\n\n";

// 1. Current Indexes
echo "1. Current Indexes on orangtua_wali:\n";
$res = $c->query("SHOW INDEXES FROM orangtua_wali");
while ($r = $res->fetch_assoc()) {
    $u = ($r['Non_unique'] == 0) ? "UNIQUE" : "NON-UNIQUE";
    echo "   - {$r['Key_name']} | Col: {$r['Column_name']} | {$u}\n";
}

// 2. Check User 196
echo "\n2. Check User ID 196 in users and orangtua_wali:\n";
$resUser = $c->query("SELECT id, username, role_id, is_active FROM users WHERE id=196");
print_r($resUser->fetch_assoc());

$resOrtu = $c->query("SELECT id, user_id, siswa_id, nama_ayah, no_hp_ortu FROM orangtua_wali WHERE user_id=196");
$count = 0;
while ($row = $resOrtu->fetch_assoc()) {
    $count++;
    echo "   [Record {$count}] id: {$row['id']}, siswa_id: {$row['siswa_id']}, nama_ayah: {$row['nama_ayah']}, no_hp: {$row['no_hp_ortu']}\n";
}

// 3. Test insert simulation
echo "\n3. Test Simulation Insert Multiple Students with Parent user_id = 196:\n";
$c->begin_transaction();
try {
    $firstSiswa = $c->query("SELECT id FROM siswa LIMIT 1")->fetch_assoc()['id'];
    $test = $c->query("INSERT INTO orangtua_wali (user_id, siswa_id, nama_ayah) VALUES (196, $firstSiswa, 'SIMULATION SIBLING')");
    if ($test) {
        echo "   [SUCCESS] Multiple records with user_id = 196 can be inserted without constraint violation!\n";
    } else {
        echo "   [FAILED] " . $c->error . "\n";
    }
} catch (\Exception $e) {
    echo "   [EXCEPTION] " . $e->getMessage() . "\n";
}
$c->rollback();
echo "\n=== END DIAGNOSTIC REPORT ===\n";
