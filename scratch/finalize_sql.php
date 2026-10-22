<?php
$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');
$sql = str_replace('â€“', '-', $sql);

// Add cleanup of dangling anggota_rombel
$cleanupAR = "\n-- 3. HAPUS RECORD DANGLING ANGGOTA_ROMBEL TANPA SISWA (ID: 747, 749 di Opal, 615 di Topaz)\nDELETE FROM anggota_rombel WHERE id IN (747, 749, 615);\n\nSET FOREIGN_KEY_CHECKS = 1;\n";
$sql = preg_replace('/SET FOREIGN_KEY_CHECKS = 1;\s*$/', $cleanupAR, $sql);

file_put_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql', $sql);
echo "Cleaned docs/perbaikan_semua_kelas_dapodik_2026_2027.sql successfully!\n";
