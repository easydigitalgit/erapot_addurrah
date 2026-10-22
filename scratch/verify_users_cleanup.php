<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== VERIFY USERS TO DELETE ===\n";

$siswaUserIds = $mysqli->query("SELECT user_id FROM siswa WHERE id BETWEEN 581 AND 620 AND user_id IS NOT NULL")->fetch_all(MYSQLI_NUM);
$siswaUserIds = array_column($siswaUserIds, 0);

$otUserIds = $mysqli->query("SELECT user_id FROM orangtua_wali WHERE siswa_id BETWEEN 581 AND 620 AND user_id IS NOT NULL")->fetch_all(MYSQLI_NUM);
$otUserIds = array_column($otUserIds, 0);

$allUserIds = array_unique(array_merge($siswaUserIds, $otUserIds));
echo "Total User IDs to delete: " . count($allUserIds) . "\n";

// Check if any of these user IDs are used by other siswa
$uList = implode(',', $allUserIds);
$otherSiswa = $mysqli->query("SELECT id, nis, nama_lengkap, user_id FROM siswa WHERE user_id IN ($uList) AND id NOT BETWEEN 581 AND 620")->fetch_all(MYSQLI_ASSOC);
echo "Used by other siswa: " . count($otherSiswa) . "\n";
print_r($otherSiswa);

// Check if any of these user IDs are used by other orangtua_wali
$otherOt = $mysqli->query("SELECT id, siswa_id, user_id FROM orangtua_wali WHERE user_id IN ($uList) AND siswa_id NOT BETWEEN 581 AND 620")->fetch_all(MYSQLI_ASSOC);
echo "Used by other orangtua_wali: " . count($otherOt) . "\n";
print_r($otherOt);

// Check if used in guru_tendik
$guru = $mysqli->query("SELECT id, nama_lengkap, user_id FROM guru_tendik WHERE user_id IN ($uList)")->fetch_all(MYSQLI_ASSOC);
echo "Used in guru_tendik: " . count($guru) . "\n";
print_r($guru);
