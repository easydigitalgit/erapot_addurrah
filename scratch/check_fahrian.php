<?php
require __DIR__ . '/../backend/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$fileMM = __DIR__ . '/../docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Granit (MM).xlsx';
$filePAIBP = __DIR__ . '/../docs/data_nilai_sts_ganjil_2026/Template_Kolektif_Tengah_Granit (PAIBP).xlsx';

$sheetMM = IOFactory::load($fileMM)->getActiveSheet();
$sheetPA = IOFactory::load($filePAIBP)->getActiveSheet();

echo "MM Row 21: NIS = '" . $sheetMM->getCell('B21')->getValue() . "', Nama = '" . $sheetMM->getCell('C21')->getValue() . "'\n";
echo "PAIBP Row 21: NIS = '" . $sheetPA->getCell('B21')->getValue() . "', Nama = '" . $sheetPA->getCell('C21')->getValue() . "'\n";

$mysqli = new mysqli('localhost', 'root', '', 'raporsmpit');
$q = $mysqli->query("SELECT s.id, s.nis, s.nama_lengkap, ar.rombel_id, r.nama_rombel 
                     FROM siswa s 
                     LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id 
                     LEFT JOIN rombel r ON r.id = ar.rombel_id 
                     WHERE s.nama_lengkap LIKE '%Fahrian%' OR s.nis LIKE '%0045%'");
while ($r = $q->fetch_assoc()) {
    print_r($r);
}
