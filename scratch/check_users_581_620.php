<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== USERS ASSOCIATED WITH SISWA 581-620 ===\n";
$res = $mysqli->query("SELECT s.id, s.user_id, s.nis, s.nama_lengkap, u.username 
                       FROM siswa s 
                       LEFT JOIN users u ON s.user_id = u.id 
                       WHERE s.id BETWEEN 581 AND 620");
$userIds = [];
while ($r = $res->fetch_assoc()) {
    if ($r['user_id']) $userIds[] = $r['user_id'];
    echo "Siswa ID: {$r['id']} | User ID: {$r['user_id']} | Username: {$r['username']} | NIS: {$r['nis']}\n";
}
echo "\nTotal User IDs: " . count($userIds) . "\n";
echo "User ID List: " . implode(',', $userIds) . "\n";
