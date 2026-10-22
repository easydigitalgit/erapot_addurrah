<?php
$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');
// Replace corrupted en-dash characters with clean hyphen
$sql = str_replace(["\xe2\x80\x93", "â€“", "–"], '-', $sql);

// Add DELETE FROM orangtua_wali before INSERT
$target = "-- 3. RESTORE DATA ORANG TUA KE TABEL ORANGTUA_WALI\n";
$deleteOrtu = "-- 3. RESTORE DATA ORANG TUA KE TABEL ORANGTUA_WALI\nDELETE FROM orangtua_wali WHERE siswa_id IN (\n    540, 550, 551, 451, 453, 461, 439, 440, 454, 629, 630, 6, 29, 36, 39, \n    40, 46, 51, 52, 54, 68, 74, 91, 94, 99, 164, 166, 176, 187, 137, \n    208, 209, 211, 222, 233, 243, 246, 256, 267\n);\n\n";

if (strpos($sql, 'DELETE FROM orangtua_wali WHERE siswa_id IN') === false) {
    $sql = str_replace($target, $deleteOrtu, $sql);
}

// Remove ON DUPLICATE KEY UPDATE from orangtua_wali at the end
$sql = preg_replace('/ON DUPLICATE KEY UPDATE nama_ayah=VALUES\(nama_ayah\), nama_ibu=VALUES\(nama_ibu\);/', ';', $sql);

file_put_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql', $sql);
echo "SQL file updated successfully!\n";
