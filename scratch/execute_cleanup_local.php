<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== TESTING CLEANUP SQL ===\n";

$mysqli->begin_transaction();

// 1. Get user_ids to delete
$getUsersQ = "SELECT DISTINCT user_id FROM siswa WHERE id BETWEEN 581 AND 620 AND user_id IS NOT NULL";
$resU = $mysqli->query($getUsersQ);
$uIds = [];
while ($r = $resU->fetch_assoc()) $uIds[] = $r['user_id'];

// Check parent users that are ONLY used by 581-620
$getOtUsersQ = "SELECT DISTINCT user_id FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620 AND user_id IS NOT NULL";
$resOt = $mysqli->query($getOtUsersQ);
while ($r = $resOt->fetch_assoc()) {
    $uid = $r['user_id'];
    $cntOther = $mysqli->query("SELECT COUNT(*) as c FROM orangtua_wali WHERE user_id = $uid AND (siswa_id NOT BETWEEN 581 AND 620 OR siswa_id IS NULL)")->fetch_assoc()['c'];
    if ($cntOther == 0) {
        $uIds[] = $uid;
    }
}
$uIds = array_unique($uIds);

echo "Users to safely delete: " . count($uIds) . "\n";

// Execute Deletions
$delOt = $mysqli->query("DELETE FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620");
echo "Deleted from orangtua_wali: " . $mysqli->affected_rows . " rows\n";

$delSiswa = $mysqli->query("DELETE FROM siswa WHERE id BETWEEN 581 AND 620");
echo "Deleted from siswa: " . $mysqli->affected_rows . " rows\n";

$uListStr = implode(',', $uIds);
$delUsers = $mysqli->query("DELETE FROM users WHERE id IN ($uListStr)");
echo "Deleted from users: " . $mysqli->affected_rows . " rows\n";

$mysqli->commit();
echo "TRANSACTION COMMITTED SUCCESSFULLY!\n";
