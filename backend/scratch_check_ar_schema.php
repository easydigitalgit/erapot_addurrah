<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
$res = $mysqli->query("SHOW CREATE TABLE anggota_rombel");
$row = $res->fetch_assoc();
print_r($row);
