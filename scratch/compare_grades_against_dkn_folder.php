<?php
require 'backend/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$dir = 'docs/data_nilai_sts_ganjil_2026/DKN & LM STS Kelas 8 T.A. 2026-2027';
$files = glob("$dir/*.xlsx");

$rombelMap = [
    'GRANIT'  => 72,
    'TOPAZ'   => 73,
    'INTAN'   => 74,
    'KYANITE' => 75,
    'SAFIR'   => 76,
    '8 GRANIT'  => 72,
    '8 TOPAZ'   => 73,
    '8 INTAN'   => 74,
    '8 KYANITE' => 75,
    '8 SAFIR'   => 76,
];

$mapelMap = [
    'BAHASA ARAB'       => 10,
    'BAHASA INDONESIA'  => 3,
    'BAHASA INGGRIS'    => 6,
    'HADITS'            => 11,
    'HADIST'            => 11,
    'INFORMATIKA'       => 7,
    'IPA'               => 5,
    'IPS'               => 14,
    'MATEMATIKA'        => 4,
    'NAHWU'             => 44,
    'PAIBP'             => 1,
    'PJOK'              => 9,
    'PKN'               => 2,
    'PP'                => 2,
    'SENI BUDAYA'       => 8,
];

echo "======================================================================\n";
echo "AUDIT SELURUH FILE DI FOLDER DKN & LM STS KELAS 8 T.A. 2026-2027\n";
echo "======================================================================\n";

$fileReports = [];

foreach ($files as $filePath) {
    $baseName = basename($filePath);
    if ($baseName === 'LM STS Kelas 8 T.A. 2026-2027.xlsx') continue;
    
    // Tebak mapel dari nama file jika di sel header tidak tertulis jelas
    $detectedMapelId = null;
    $detectedMapelName = '';
    foreach ($mapelMap as $mName => $mId) {
        if (stripos($baseName, $mName) !== false) {
            $detectedMapelId = $mId;
            $detectedMapelName = $mName;
            break;
        }
    }
    
    try {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $sheetNames = $reader->listWorksheetNames($filePath);
        
        $spreadsheet = $reader->load($filePath);
        
        $fileInfo = [
            'file' => $baseName,
            'detected_mapel' => $detectedMapelName,
            'mapel_id' => $detectedMapelId,
            'sheets' => []
        ];
        
        foreach ($sheetNames as $sName) {
            $cleanSName = strtoupper(trim($sName));
            if (stripos($cleanSName, 'DESEKRIPSI') !== false || stripos($cleanSName, 'LM') !== false) {
                continue;
            }
            
            // Cari rombel_id
            $rombelId = null;
            foreach ($rombelMap as $rKey => $rId) {
                if (stripos($cleanSName, $rKey) !== false) {
                    $rombelId = $rId;
                    break;
                }
            }
            
            $sheet = $spreadsheet->getSheetByName($sName);
            $maxRow = $sheet->getHighestDataRow();
            
            // Cek nama mapel dari sel jika ada
            $headerMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
            $actualMapelId = $detectedMapelId;
            if (!empty($headerMapel)) {
                foreach ($mapelMap as $mName => $mId) {
                    if (stripos($headerMapel, $mName) !== false) {
                        $actualMapelId = $mId;
                        break;
                    }
                }
            }
            
            // Hitung siswa di sheet
            $siswaExcelCount = 0;
            for ($r = 10; $r <= $maxRow; $r++) {
                $nama = trim((string)$sheet->getCell("B$r")->getValue());
                if (!empty($nama) && stripos($nama, 'RATA') === false && stripos($nama, 'JUMLAH') === false) {
                    $siswaExcelCount++;
                }
            }
            
            // Cek jumlah data yang ada di database raporsmpit saat ini
            $dbFormatifCount = 0;
            $dbSumatifCount = 0;
            if ($rombelId && $actualMapelId) {
                $dbFormatifCount = (int)$pdo->query("
                    SELECT COUNT(*) FROM raporsmpit.nilai_formatif 
                    WHERE rombel_id = $rombelId AND mapel_id = $actualMapelId AND tahun_ajaran_id = 10
                ")->fetchColumn();
                
                $dbSumatifCount = (int)$pdo->query("
                    SELECT COUNT(*) FROM raporsmpit.nilai_sumatif ns
                    JOIN raporsmpit.siswa s ON ns.siswa_id = s.id
                    WHERE s.rombel_id = $rombelId AND ns.mapel_id = $actualMapelId AND ns.tahun_ajaran_id = 10 AND ns.jenis_sumatif = 'pts'
                ")->fetchColumn();
            }
            
            $fileInfo['sheets'][] = [
                'sheet_name' => $sName,
                'rombel_id' => $rombelId,
                'mapel_id' => $actualMapelId,
                'excel_students' => $siswaExcelCount,
                'db_formatif' => $dbFormatifCount,
                'db_sumatif' => $dbSumatifCount,
                'status' => ($dbSumatifCount >= $siswaExcelCount && $dbFormatifCount >= ($siswaExcelCount * 2)) ? 'SUDAH SINKRON' : 'BELUM SINKRON / KURANG'
            ];
        }
        $fileReports[] = $fileInfo;
    } catch (Exception $e) {
        echo "Error reading $baseName: " . $e->getMessage() . "\n";
    }
}

file_put_contents('scratch/dkn_comparison_summary.json', json_encode($fileReports, JSON_PRETTY_PRINT));

printf("%-45s | %-12s | %-8s | %-8s | %-10s | %-15s\n", "File", "Sheet/Rombel", "Siswa Ex", "DB Form", "DB Sumatif", "Status Sinkron");
echo str_repeat("-", 108) . "\n";
foreach ($fileReports as $fr) {
    foreach ($fr['sheets'] as $sh) {
        printf("%-45s | %-12s | %-8d | %-8d | %-10d | %-15s\n",
            substr($fr['file'], 0, 45),
            $sh['sheet_name'],
            $sh['excel_students'],
            $sh['db_formatif'],
            $sh['db_sumatif'],
            $sh['status']
        );
    }
}

