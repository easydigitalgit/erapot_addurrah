<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$file = __DIR__ . '/../docs/Template_Kolektif_Tengah_Intan.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

$highestRow = $sheet->getHighestDataRow();
$highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

$ta_id_post = 10;
$stmt = $pdo->prepare("SELECT * FROM tahun_ajaran WHERE id = ?");
$stmt->execute([$ta_id_post]);
$ta_aktif = $stmt->fetch(PDO::FETCH_ASSOC);

$tahun_ajaran_id = $ta_aktif['id'];
$semester = $ta_aktif['semester'];

$userId = 30; // user luthfiachairunnisa135@gmail.com
$stmt = $pdo->prepare("SELECT id FROM guru_tendik WHERE user_id = ?");
$stmt->execute([$userId]);
$dataGuru = $stmt->fetch(PDO::FETCH_ASSOC);
$guru_id = $dataGuru ? $dataGuru['id'] : $userId;

$strKelas = trim(str_replace(':', '', (string)$sheet->getCell('C4')->getValue()));
$strMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
$strJenis = strtolower(trim(str_replace(':', '', (string)$sheet->getCell('C6')->getValue())));

$embeddedTaId = (int)$sheet->getCell('A7')->getValue();
$embeddedRombelId = (int)$sheet->getCell('B7')->getValue();
$embeddedMapelId = (int)$sheet->getCell('C7')->getValue();

$rombel = null;
if ($embeddedRombelId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM rombel WHERE id = ?");
    $stmt->execute([$embeddedRombelId]);
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$rombel) {
    $namaKelas = trim(preg_replace('/^(kelas|rombel)\s*/i', '', $strKelas));
    if (strpos($namaKelas, '-') !== false) {
        $parts = explode('-', $namaKelas);
        $namaKelas = trim(end($parts));
    }
    $stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE ? AND id_tahun_ajaran = ? LIMIT 1");
    $stmt->execute(["%$namaKelas%", $tahun_ajaran_id]);
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$rombel) {
        $stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE ? LIMIT 1");
        $stmt->execute(["%$namaKelas%"]);
        $rombel = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
echo "Resolved Rombel: ID={$rombel['id']}, Name={$rombel['nama_rombel']}, TA={$rombel['id_tahun_ajaran']}\n";
$rombel_id = $rombel['id'];

$mapel = null;
if ($embeddedMapelId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE id = ?");
    $stmt->execute([$embeddedMapelId]);
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$mapel) {
    $namaMapel = trim(preg_replace('/^(mapel|mata pelajaran)\s*/i', '', $strMapel));
    $stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE nama_mapel LIKE ? LIMIT 1");
    $stmt->execute(["%$namaMapel%"]);
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);
}
echo "Resolved Mapel: ID={$mapel['id']}, Name={$mapel['nama_mapel']}\n";
$mapel_id = $mapel['id'];

$kategori_semester = (stripos($strJenis, 'tengah') !== false) ? 'Tengah Semester' : 'Akhir Semester';
$kategori_db       = (stripos($strJenis, 'tengah') !== false) ? 'Tengah Semester' : 'Akhir Semester';

$colMap = [];
for ($c = 4; $c <= $highestColumnIndex; $c++) {
    $colLtr = Coordinate::stringFromColumnIndex($c);
    $kodeRahasia = trim((string)$sheet->getCell($colLtr . '7')->getValue());
    if (!empty($kodeRahasia)) {
        $colMap[$colLtr] = $kodeRahasia;
    }
}
echo "ColMap: " . json_encode($colMap) . "\n";

$getSafeValue = function ($colLtr, $rowNum) use ($sheet) {
    try {
        $val = $sheet->getCell($colLtr . $rowNum)->getCalculatedValue();
        if (is_string($val)) $val = str_replace(',', '.', $val);
        return (is_numeric($val) && trim((string)$val) !== '') ? (float)$val : null;
    } catch (\Exception $e) {
        return null;
    }
};

$processedCount = 0;
for ($row = 10; $row <= $highestRow; $row++) {
    $nis = trim((string)$sheet->getCell('B' . $row)->getValue());
    if (empty($nis)) continue;

    $stmt = $pdo->prepare("
        SELECT s.id, s.nis, s.nama_lengkap
        FROM anggota_rombel ar
        JOIN siswa s ON s.id = ar.siswa_id
        WHERE s.nis = ?
          AND ar.rombel_id = ?
          AND ar.tahun_ajaran_id = ?
    ");
    $stmt->execute([$nis, $rombel_id, $tahun_ajaran_id]);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$siswa) {
        $stmt = $pdo->prepare("
            SELECT s.id, s.nis, s.nama_lengkap
            FROM anggota_rombel ar
            JOIN siswa s ON s.id = ar.siswa_id
            WHERE s.nis = ?
              AND ar.rombel_id = ?
        ");
        $stmt->execute([$nis, $rombel_id]);
        $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$siswa) continue;
    $siswa_id = $siswa['id'];
    $hasUpdate = false;

    foreach ($colMap as $colLetter => $kode) {
        $val = $getSafeValue($colLetter, $row);
        if ($val === null) continue;
        $hasUpdate = true;
    }
    if ($hasUpdate) $processedCount++;
}

echo "Total processed students: $processedCount\n";
