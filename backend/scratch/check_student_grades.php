<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

$mapel_id = 5;
$ta_id = 9;
$rombel_id = 7; // Zamrud

echo "--- Details for Student Ali Amri Saidi Rambe (Siswa ID 152?) ---\n";
// Let's find the student ID first
$res = $mysqli->query("SELECT id, nama_lengkap FROM siswa WHERE nama_lengkap LIKE '%Ali Amri Saidi Rambe%'");
$siswa = $res->fetch_assoc();
$siswa_id = $siswa['id'];
echo "Siswa ID: $siswa_id\n";

echo "\n--- Formatif Records ---\n";
$sql = "SELECT pertemuan, jenis_penilaian, nilai_angka, kategori FROM nilai_formatif 
        WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "Pertemuan: " . $row['pertemuan'] . " | Jenis: " . $row['jenis_penilaian'] . " | Nilai: " . $row['nilai_angka'] . " | Kategori: " . $row['kategori'] . "\n";
}

echo "\n--- Sumatif Records ---\n";
$sql = "SELECT jenis_sumatif, nilai_angka, kategori FROM nilai_sumatif 
        WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "Jenis: " . $row['jenis_sumatif'] . " | Nilai: " . $row['nilai_angka'] . " | Kategori: " . $row['kategori'] . "\n";
}

echo "\n--- Nilai Rapor Record ---\n";
$sql = "SELECT rata_formatif, rata_sumatif, nilai_akhir, kategori FROM nilai_rapor 
        WHERE siswa_id = $siswa_id AND mapel_id = $mapel_id AND tahun_ajaran_id = $ta_id";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "Kategori: " . $row['kategori'] . " | Rata Formatif: " . $row['rata_formatif'] . " | Rata Sumatif: " . $row['rata_sumatif'] . " | Nilai Akhir: " . $row['nilai_akhir'] . "\n";
}

$mysqli->close();
