<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$r = $m->query('SELECT id, nis, nisn, nik, nama_lengkap, rombel_id FROM siswa WHERE id = 540')->fetch_assoc();
print_r($r);
