<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

$sheetsToScan = ['VII', 'VIII', 'IX'];
$excelClasses = [];

foreach ($sheetsToScan as $sName) {
    $sheet = $spreadsheet->getSheetByName($sName);
    $highestRow = $sheet->getHighestDataRow();
    
    $currentClass = null;
    
    for ($r = 1; $r <= $highestRow; $r++) {
        $valA = trim((string)$sheet->getCell('A' . $r)->getValue());
        $valB = trim((string)$sheet->getCell('B' . $r)->getValue());
        $valC = trim((string)$sheet->getCell('C' . $r)->getValue());
        $valD = trim((string)$sheet->getCell('D' . $r)->getValue());
        $valAH = trim((string)$sheet->getCell('AH' . $r)->getValue());

        // Check if row indicates class name header
        // e.g. A is string without number, B is empty or similar
        if (preg_match('/^(VII|VIII|IX)\s*[-_ ]*\s*(AMAZONITE|OPAL|ZAMRUD|CARNELIAN|RUBY|GRANIT|TOPAZ|INTAN|KYANITE|SAFIR|EMERALD|FAIRUZ|BIDURI|CRYSTAL|DIAMOND)$/i', $valA, $m) ||
            preg_match('/^(AMAZONITE|OPAL|ZAMRUD|CARNELIAN|RUBY|GRANIT|TOPAZ|INTAN|KYANITE|SAFIR|EMERALD|FAIRUZ|BIDURI|CRYSTAL|DIAMOND)$/i', $valA, $m)) {
            $className = strtoupper($m[1] . (isset($m[2]) ? ' ' . $m[2] : ''));
            // If just class name without level, add level
            if (!preg_match('/^(VII|VIII|IX)/', $className)) {
                $className = $sName . ' ' . $className;
            }
            $currentClass = $className;
            $excelClasses[$currentClass] = [];
            continue;
        }

        // If valA is numeric, it is a student row
        if (is_numeric($valA) && !empty($valB) && $valB !== 'Nama' && $currentClass) {
            $excelClasses[$currentClass][] = [
                'row' => $r,
                'no' => (int)$valA,
                'nama' => $valB,
                'nipd' => $valC,
                'nisn' => $valD,
                'rombel_saat_ini' => $valAH
            ];
        }
    }
}

echo "=== CLASSES FOUND IN EXCEL ===\n";
foreach ($excelClasses as $cName => $students) {
    echo "$cName: " . count($students) . " siswa\n";
}
