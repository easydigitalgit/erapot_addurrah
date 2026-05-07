<?php
$db = mysqli_connect('localhost', 'dev', '12345678', 'raporsmpit');
$resR = mysqli_query($db, "SELECT id FROM rombel WHERE nama_rombel = 'Emerald'");
$rombel = mysqli_fetch_assoc($resR);
$rombel_id = $rombel['id'];
echo "Rombel Emerald ID: $rombel_id" . PHP_EOL;

$resS1 = mysqli_query($db, "SELECT COUNT(*) as total FROM siswa WHERE rombel_id = '$rombel_id' AND status_siswa = 'Aktif'");
$rowS1 = mysqli_fetch_assoc($resS1);
echo "Students in siswa table for rombel $rombel_id: " . $rowS1['total'] . PHP_EOL;

$resS2 = mysqli_query($db, "SELECT COUNT(*) as total FROM anggota_rombel WHERE rombel_id = '$rombel_id' AND tahun_ajaran_id = 9");
$rowS2 = mysqli_fetch_assoc($resS2);
echo "Students in anggota_rombel table for rombel $rombel_id (TA 9): " . $rowS2['total'] . PHP_EOL;
