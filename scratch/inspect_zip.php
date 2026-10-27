<?php
$zipFile = 'docs/data_nilai_sts_ganjil_2026/LM dan DKN STS B Inggris Kelas 8 (Intan, Kyanite, Safir, Granit & Topaz) Oktober 2026.zip';
if (file_exists($zipFile)) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        echo "Files inside B Inggris zip:\n";
        for ($i = 0; $i < $zip->numFiles; $i++) {
            echo "  - " . $zip->getNameIndex($i) . "\n";
        }
        $zip->close();
    }
}

