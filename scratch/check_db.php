<?php
$env = file_get_contents(__DIR__ . '/../backend/.env');
preg_match('/database.default.hostname\s*=\s*(.*)/', $env, $mHost);
preg_match('/database.default.database\s*=\s*(.*)/', $env, $mDb);
preg_match('/database.default.username\s*=\s*(.*)/', $env, $mUser);
preg_match('/database.default.password\s*=\s*(.*)/', $env, $mPass);

$pdo = new PDO("mysql:host=" . trim($mHost[1]) . ";dbname=" . trim($mDb[1]), trim($mUser[1]), trim($mPass[1]));

echo "--- Check email_siswa in ('1','2','3','4','5','6','7','8','9','10') in siswa ---\n";
$stmt = $pdo->query("SELECT id, nis, nama_lengkap, email_siswa FROM siswa WHERE email_siswa IN ('1','2','3','4','5','6','7','8','9','10')");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}, NIS: {$row['nis']}, Nama: {$row['nama_lengkap']}, Email: '{$row['email_siswa']}'\n";
}

echo "\n--- Check email in ('1','2','3','4','5','6','7','8','9','10') in users ---\n";
$stmt = $pdo->query("SELECT id, username, email FROM users WHERE email IN ('1','2','3','4','5','6','7','8','9','10')");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}, Username: {$row['username']}, Email: '{$row['email']}'\n";
}
