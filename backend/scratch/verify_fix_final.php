<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

$mapel_id = 5;
$ta_id = 9;
$rombel_id = 7; // Zamrud

echo "--- Final Verification of Calculation Logic ---\n";

// 1. Fetch ALL formatifs for this class/mapel/ta
$sql = "SELECT siswa_id, pertemuan, jenis_penilaian, nilai_angka FROM nilai_formatif 
        WHERE mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id AND rombel_id = $rombel_id";
$res = $mysqli->query($sql);
$formatifs = [];
while($row = $res->fetch_assoc()) $formatifs[] = $row;

// 2. Calculate Pembagi (New Logic)
$max_nh_pert = 0;
$max_uh_pert = 0;
foreach ($formatifs as $f) {
    $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
    $pert = (int)($f['pertemuan'] ?? 0);
    $nilai = (float)($f['nilai_angka'] ?? 0);

    if ($nilai > 0) {
        if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
            if ($pert > $max_uh_pert) $max_uh_pert = $pert;
        } else {
            if ($pert > $max_nh_pert) $max_nh_pert = $pert;
        }
    }
}
$pembagi_nh = $max_nh_pert > 0 ? $max_nh_pert : 1;
$pembagi_uh = $max_uh_pert > 0 ? $max_uh_pert : 1;

echo "Divisor NH: $pembagi_nh\n";
echo "Divisor UH: $pembagi_uh\n";

// 3. Calculate for Student Ali Amri (422)
$sum_nh = 0;
$sum_uh = 0;
foreach ($formatifs as $f) {
    if ($f['siswa_id'] == 422) {
        $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
        $nilai = (float)($f['nilai_angka'] ?? 0);
        if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
            $sum_uh += $nilai;
        } else {
            $sum_nh += $nilai;
        }
    }
}

$avg_nh = $sum_nh / $pembagi_nh;
$avg_uh = $sum_uh / $pembagi_uh;

echo "Student 422:\n";
echo "  Sum NH: $sum_nh -> Rata NH: $avg_nh\n";
echo "  Sum UH: $sum_uh -> Rata UH: $avg_uh\n";

if ($avg_nh == 95 && $avg_uh == 95) {
    echo "\nSUCCESS: Calculation is now correct (95)!\n";
} else {
    echo "\nFAILURE: Calculation is still $avg_nh\n";
}

$mysqli->close();
