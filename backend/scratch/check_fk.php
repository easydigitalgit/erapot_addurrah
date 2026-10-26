<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
$res = $mysqli->query('SELECT * FROM users WHERE id IN (241, 231, 452, 456, 476)');
while ($r = $res->fetch_assoc()) {
    echo "User ID: {$r['id']} | Username: {$r['username']} | Role: {$r['role_id']}\n";
}

// Check Foreign Keys on orangtua_wali
$fks = $mysqli->query("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_NAME = 'orangtua_wali' AND REFERENCED_TABLE_NAME IS NOT NULL
");
echo "\nForeign keys on orangtua_wali:\n";
while ($fk = $fks->fetch_assoc()) {
    print_r($fk);
}
