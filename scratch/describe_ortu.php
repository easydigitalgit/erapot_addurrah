<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$res = $m->query("DESCRIBE orangtua_wali");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . ' (' . $r['Type'] . ') Null=' . $r['Null'] . PHP_EOL;
}
