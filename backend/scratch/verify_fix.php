<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

$mapel_id = 5;
$ta_id = 9;
$rombel_id = 7; // Zamrud

echo "--- Simulating NEW getData() Logic for Zamrud ---\n";

// Fetch formatifs with rombel_id filter
$sql = "SELECT pertemuan, jenis_penilaian, nilai_angka FROM nilai_formatif 
        WHERE mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id AND rombel_id = $rombel_id";
$res = $mysqli->query($sql);
$formatifs = [];
while($row = $res->fetch_assoc()) $formatifs[] = $row;

// New _getPembagiDinamis logic
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

echo "Calculated Divisor NH: $pembagi_nh\n";
echo "Calculated Divisor UH: $pembagi_uh\n";

// Check a sample student (Ali Amri)
$siswa_id = 422;
$sum_nh = 0;
$sum_uh = 0;
foreach ($formatifs as $f) {
    if ($f['siswa_id'] == $siswa_id || true) { // Simulating for any student in Zamrud from the list
        // In the real code it filters by student id, let's just take one student's records
    }
}

// Better simulation: find scores for student 422
$res = $mysqli->query("SELECT nilai_angka, jenis_penilaian FROM nilai_formatif WHERE siswa_id = 422 AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id");
$sum_nh = 0;
$sum_uh = 0;
while($row = $res->fetch_assoc()) {
    if (strpos(strtoupper($row['jenis_penilaian']), 'UH') !== false) $sum_uh += $row['nilai_angka'];
    else $sum_nh += $row['nilai_angka'];
}

echo "Student 422 Sum NH: $sum_nh\n";
echo "Student 422 Rata NH (Calculated): " . ($sum_nh / $pembagi_nh) . "\n";

$mysqli->close();
