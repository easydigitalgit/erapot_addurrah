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

$strKelas = trim(str_replace(':', '', (string)$sheet->getCell('C4')->getValue()));
$strMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
$strJenis = strtolower(trim(str_replace(':', '', (string)$sheet->getCell('C6')->getValue())));

$ta_id = 10;
$stmt = $pdo->prepare("SELECT * FROM tahun_ajaran WHERE id = ?");
$stmt->execute([$ta_id]);
$ta_aktif = $stmt->fetch(PDO::FETCH_ASSOC);
$semester = $ta_aktif['semester'];

// Look up rombel WITH tahun_ajaran filter:
$stmt = $pdo->prepare("SELECT * FROM rombel WHERE nama_rombel LIKE ? AND id_tahun_ajaran = ?");
$stmt->execute(["%$strKelas%", $ta_id]);
$rombel = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Matched Rombel: " . json_encode($rombel) . "\n";
$rombel_id = $rombel['id'];

// Look up mapel:
$stmt = $pdo->prepare("SELECT * FROM mata_pelajaran WHERE nama_mapel LIKE ?");
$stmt->execute(["%$strMapel%"]);
$mapel = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Matched Mapel: " . json_encode($mapel) . "\n";
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
          AND ar.semester = ?
    ");
    $stmt->execute([$nis, $rombel_id, $ta_id, $semester]);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$siswa) {
        echo "Row $row ($nis) NOT FOUND!\n";
        continue;
    }
    $siswa_id = $siswa['id'];
    $hasUpdate = false;

    foreach ($colMap as $colLetter => $kode) {
        $val = $getSafeValue($colLetter, $row);
        if ($val === null) continue;

        echo "Row $row: Student {$siswa['nama_lengkap']} | $kode = $val\n";
        $hasUpdate = true;
    }

    if ($hasUpdate) $processedCount++;
}

echo "Total processed: $processedCount\n";
