<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
$res = $mysqli->query("SELECT * FROM anggota_rombel WHERE siswa_id = 89");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
