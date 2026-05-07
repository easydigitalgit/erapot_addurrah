<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

$mapel_id = 5;
$ta_id = 9;
$rombel_id = 7; // Zamrud
$siswa_id = 422;

echo "--- Nilai Rapor Record for Siswa 422 ---\n";
$sql = "SELECT rata_formatif, rata_sumatif, nilai_akhir, kategori FROM nilai_rapor 
        WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "Kategori: " . $row['kategori'] . " | Rata Formatif: " . $row['rata_formatif'] . " | Rata Sumatif: " . $row['rata_sumatif'] . " | Nilai Akhir: " . $row['nilai_akhir'] . "\n";
}

$mysqli->close();
