<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$res = $m->query('DESCRIBE siswa');
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . ' (' . $r['Type'] . ') Null=' . $r['Null'] . ' Key=' . $r['Key'] . PHP_EOL;
}
