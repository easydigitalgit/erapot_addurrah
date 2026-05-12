<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
$res = $mysqli->query("SELECT id, tahun, semester, status FROM tahun_ajaran WHERE status = 'Aktif'");
print_r($res->fetch_assoc());
