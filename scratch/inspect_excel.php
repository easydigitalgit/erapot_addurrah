<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = __DIR__ . '/../docs/Template_Kolektif_Tengah_Intan.xlsx';
if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

echo 'Highest Row: ' . $sheet->getHighestDataRow() . PHP_EOL;
echo 'Highest Col: ' . $sheet->getHighestDataColumn() . PHP_EOL;
echo 'C4 (Kelas): ' . var_export($sheet->getCell('C4')->getValue(), true) . PHP_EOL;
echo 'C5 (Mapel): ' . var_export($sheet->getCell('C5')->getValue(), true) . PHP_EOL;
echo 'C6 (Jenis): ' . var_export($sheet->getCell('C6')->getValue(), true) . PHP_EOL;

echo 'Row 7 (Kode Rahasia):' . PHP_EOL;
$highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
for ($c = 1; $c <= $highestColIndex; $c++) {
    $ltr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $val = $sheet->getCell($ltr . '7')->getValue();
    if ($val) echo "$ltr 7: $val | ";
}
echo PHP_EOL;

echo 'Row 8 (Headers):' . PHP_EOL;
for ($c = 1; $c <= $highestColIndex; $c++) {
    $ltr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $val = $sheet->getCell($ltr . '8')->getValue();
    if ($val) echo "$ltr 8: $val | ";
}
echo PHP_EOL;

echo 'Row 9 (Subheaders):' . PHP_EOL;
for ($c = 1; $c <= $highestColIndex; $c++) {
    $ltr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
    $val = $sheet->getCell($ltr . '9')->getValue();
    if ($val) echo "$ltr 9: $val | ";
}
echo PHP_EOL;

echo "\nRows data:\n";
for ($r = 10; $r <= $sheet->getHighestDataRow(); $r++) {
    $nis = $sheet->getCell('B' . $r)->getValue();
    $nama = $sheet->getCell('C' . $r)->getValue();
    echo "Row $r: NIS=$nis, Nama=$nama\n";
    for ($c = 4; $c <= $highestColIndex; $c++) {
        $ltr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        $val = $sheet->getCell($ltr . $r)->getCalculatedValue();
        if ($val !== null && trim((string)$val) !== '') {
            echo "   $ltr$r = $val (raw: " . var_export($sheet->getCell($ltr . $r)->getValue(), true) . ")\n";
        }
    }
}
