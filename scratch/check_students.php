<?php
$env = file_get_contents(__DIR__ . '/../backend/.env');
preg_match('/database.default.hostname\s*=\s*(.*)/', $env, $mHost);
preg_match('/database.default.database\s*=\s*(.*)/', $env, $mDb);
preg_match('/database.default.username\s*=\s*(.*)/', $env, $mUser);
preg_match('/database.default.password\s*=\s*(.*)/', $env, $mPass);

$pdo = new PDO("mysql:host=" . trim($mHost[1]) . ";dbname=" . trim($mDb[1]), trim($mUser[1]), trim($mPass[1]));

$names = ['Al Buqhori Rafif', 'Alvaro Rafiqi Iranda Nst', 'ARKA ZEVAN ABADI', 'Bahri Habibie'];
foreach ($names as $name) {
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nama_lengkap LIKE ?");
    $stmt->execute(['%' . $name . '%']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found for '$name': " . count($rows) . " rows\n";
    foreach ($rows as $r) {
        echo "  ID: {$r['id']}, NIS: {$r['nis']}, Email: '{$r['email_siswa']}'\n";
    }
}
