<?php
$files = glob('docs/data_nilai_sts_ganjil_2026/*');
foreach ($files as $f) {
    echo basename($f) . " (" . filesize($f) . " bytes)\n";
}

