<?php
$avg_nh = 91.25; $w_nh = 0.35;
$avg_uh = 87.5; $w_uh = 0.35;
$val_sts = 90; $w_sts = 0.15;
$val_sas = 96; $w_sas = 0.15;

$c_nh  = floor(($avg_nh * $w_nh) * 10) / 10;
$c_uh  = floor(($avg_uh * $w_uh) * 10) / 10;
$c_sts = floor(($val_sts * $w_sts) * 10) / 10;
$c_sas = floor(($val_sas * $w_sas) * 10) / 10;

$kalkulasi = $c_nh + $c_uh + $c_sts + $c_sas;
$nilai_akhir = number_format($kalkulasi, 1, '.', '');

echo "VERIFIKASI LOGIKA TRUNCATION TIAP KOMPONEN:\n";
echo "NH: floor(" . ($avg_nh * $w_nh) . " * 10) / 10 = $c_nh\n";
echo "UH: floor(" . ($avg_uh * $w_uh) . " * 10) / 10 = $c_uh\n";
echo "STS: floor(" . ($val_sts * $w_sts) . " * 10) / 10 = $c_sts\n";
echo "SAS: floor(" . ($val_sas * $w_sas) . " * 10) / 10 = $c_sas\n";
echo "TOTAL AKHIR: $nilai_akhir\n";
