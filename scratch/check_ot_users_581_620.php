<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== ORANG TUA USERS FOR 581-620 ===\n";
$res = $mysqli->query("SELECT ot.id, ot.siswa_id, ot.user_id, u.username 
                       FROM orangtua_wali ot 
                       LEFT JOIN users u ON ot.user_id = u.id 
                       WHERE ot.siswa_id BETWEEN 581 AND 620");
$otUserIds = [];
while ($r = $res->fetch_assoc()) {
    if ($r['user_id']) $otUserIds[] = $r['user_id'];
    echo "OT ID: {$r['id']} | Siswa ID: {$r['siswa_id']} | User ID: {$r['user_id']} | Username: {$r['username']}\n";
}
echo "Total OT user IDs: " . count(array_unique($otUserIds)) . "\n";
echo "OT User IDs: " . implode(',', array_unique($otUserIds)) . "\n";
