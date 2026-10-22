<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$names = [
    "Alifa Nur Zahirah",
    "ALYA JAZILA ARANI",
    "Aqilah Khanza Ramadhani",
    "EARLYTA ARSYFA",
    "Inaya Athifa",
    "SALWA TSABITA",
    "SYIFA NAURA AFAF"
];

foreach ($names as $name) {
    $res = $mysqli->query("SELECT id, nis, nisn, nama_lengkap, rombel_id, status_siswa FROM siswa WHERE nama_lengkap LIKE '%$name%'");
    if ($res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "FOUND IN SISWA: ID=" . $r['id'] . " | " . $r['nama_lengkap'] . " | rombel_id=" . $r['rombel_id'] . " | status=" . $r['status_siswa'] . "\n";
        }
    } else {
        echo "NOT FOUND IN SISWA: $name\n";
    }
}
