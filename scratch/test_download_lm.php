<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=raporsmpit;charset=utf8mb4", "root", "");

$ta_id = 10;
$rombel_id = 77;
$mapel_id = 6;
$jenis = 'tengah';

$stmt = $pdo->prepare("SELECT * FROM tahun_ajaran WHERE id = ?");
$stmt->execute([$ta_id]);
$taData = $stmt->fetch(PDO::FETCH_ASSOC);
$semesterAktif = $taData ? $taData['semester'] : 'Ganjil';

$stmt = $pdo->prepare("SELECT * FROM rombel WHERE id = ?");
$stmt->execute([$rombel_id]);
$rombel = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Rombel tingkat: " . $rombel['tingkat'] . "\n";
$tingkatClean = preg_replace('/[^0-9]/', '', (string)$rombel['tingkat']);
if (empty($tingkatClean)) {
    $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
    $tingkatClean = $romToNum[strtoupper(trim($rombel['tingkat']))] ?? 0;
}
echo "Tingkat clean: $tingkatClean\n";

$sql = "SELECT COUNT(*) FROM master_lm WHERE mapel_id = ? AND tingkat = ? AND semester = ? AND kategori IN ('Tengah', 'STS', 'PTS') AND status = 'Aktif'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mapel_id, $tingkatClean, $semesterAktif]);
$count = $stmt->fetchColumn();
echo "Count LM: $count\n";
