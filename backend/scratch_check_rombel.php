<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
$res = $mysqli->query("SELECT id, nama_rombel FROM rombel WHERE id IN (18, 19)");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
