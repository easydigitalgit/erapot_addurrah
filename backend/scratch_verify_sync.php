<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
$res = $mysqli->query("SELECT s.nama_lengkap FROM anggota_rombel ar JOIN siswa s ON s.id = ar.siswa_id WHERE ar.rombel_id = 19 AND ar.tahun_ajaran_id = 9 AND ar.semester = 'Genap'");
echo "DAFTAR SISWA KYANITE (MESIN WAKTU):\n";
while($row = $res->fetch_assoc()) {
    echo "- " . $row['nama_lengkap'] . "\n";
}
