<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$files = [
    'PAIBP' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/PAIBP (8 Topaz, 8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'B_INDO' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Indonesia (8 Topaz).xlsx',
    'B_ARAB' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Bahasa Arab (8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'NAHWU' => 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027/Nahwu (8 Intan, 8 Kyanite, 8 Safir).xlsx',
    'DKN_ARAB' => 'docs/data_nilai_sts_ganjil_2026/DKN BAHASA ARAB VIII STS Ganjil.xlsx',
    'DKN_NAHWU' => 'docs/data_nilai_sts_ganjil_2026/DKN NAHWU VIII STS Ganjil.xlsx'
];

foreach ($files as $name => $fpath) {
    if (!file_exists($fpath)) continue;
    $ss = IOFactory::load($fpath);
    echo "=== Mencari di $name ($fpath) ===\n";
    foreach ($ss->getSheetNames() as $sname) {
        $sheet = $ss->getSheetByName($sname);
        for ($r = 1; $r <= $sheet->getHighestRow(); $r++) {
            $rowStr = '';
            for ($c = 'A'; $c <= 'F'; $c++) {
                $val = trim((string)$sheet->getCell($c . $r)->getFormattedValue());
                if ($val) $rowStr .= "$val | ";
            }
            if (stripos($rowStr, 'IQBAL') !== false || stripos($rowStr, 'NAUFAL') !== false || stripos($rowStr, 'AFK') !== false) {
                echo "  [$sname Row $r]: $rowStr\n";
            }
        }
    }
}

