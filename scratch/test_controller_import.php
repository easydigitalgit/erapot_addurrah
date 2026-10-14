<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userEmail = 'luthfiachairunnisa135@gmail.com';
$stmt = $pdo->prepare("SELECT u.id as user_id, gt.id as guru_id FROM users u LEFT JOIN guru_tendik gt ON gt.user_id = u.id WHERE u.email = ?");
$stmt->execute([$userEmail]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

echo "User: ID {$userData['user_id']}, Guru ID {$userData['guru_id']}\n";

$ta_aktif = $pdo->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif'")->fetch(PDO::FETCH_ASSOC);
$tahun_ajaran_id = $ta_aktif['id'];
$semester = $ta_aktif['semester'];

echo "TA Aktif: {$ta_aktif['tahun']} ({$semester}), ID: $tahun_ajaran_id\n";

$file = __DIR__ . '/../docs/Template_Kolektif_Tengah_Intan.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestDataRow();
$highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

$strKelas = trim(str_replace(':', '', (string)$sheet->getCell('C4')->getValue()));
$strMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
$strJenis = strtolower(trim(str_replace(':', '', (string)$sheet->getCell('C6')->getValue())));

$namaKelas = trim(preg_replace('/^(kelas|rombel)\s*/i', '', $strKelas));
if (strpos($namaKelas, '-') !== false) {
    $parts = explode('-', $namaKelas);
    $namaKelas = trim(end($parts));
}

$stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE ? AND id_tahun_ajaran = ? LIMIT 1");
$stmt->execute(["%$namaKelas%", $tahun_ajaran_id]);
$rombel = $stmt->fetch(PDO::FETCH_ASSOC);

$namaMapel = trim(preg_replace('/^(mapel|mata pelajaran)\s*/i', '', $strMapel));
$stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE nama_mapel LIKE ? LIMIT 1");
$stmt->execute(["%$namaMapel%"]);
$mapel = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Target Rombel: {$rombel['id']} ({$rombel['nama_rombel']})\n";
echo "Target Mapel: {$mapel['id']} ({$mapel['nama_mapel']})\n";

// Count current grades before import
$stmt = $pdo->prepare("SELECT count(*) as c FROM nilai_formatif WHERE rombel_id = ? AND mapel_id = ? AND tahun_ajaran_id = ?");
$stmt->execute([$rombel['id'], $mapel['id'], $tahun_ajaran_id]);
echo "Nilai Formatif count before: " . $stmt->fetch(PDO::FETCH_ASSOC)['c'] . "\n";

$stmt = $pdo->prepare("SELECT count(*) as c FROM nilai_sumatif WHERE mapel_id = ? AND tahun_ajaran_id = ?");
$stmt->execute([$mapel['id'], $tahun_ajaran_id]);
echo "Nilai Sumatif count before: " . $stmt->fetch(PDO::FETCH_ASSOC)['c'] . "\n";
