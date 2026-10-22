<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$m->query('DELETE FROM anggota_rombel WHERE id IN (747, 749, 615)');
echo 'Deleted dangling orphan anggota_rombel: ' . $m->affected_rows . PHP_EOL;
