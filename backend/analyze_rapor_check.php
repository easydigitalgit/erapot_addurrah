<?php
require_once __DIR__ . '/vendor/autoload.php';
$db = \Config\Database::connect();

// Find rombel kelas 8
$rombelList = $db->query("SELECT r.id, r.nama_rombel, r.tingkat, r.id_tahun_ajaran, ta.tahun, ta.semester, ta.status 
FROM rombel r 
LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran 
WHERE r.tingkat LIKE '%8%' OR r.tingkat LIKE '%VIII%' 
ORDER BY r.id_tahun_ajaran DESC, r.nama_rombel")->getResultArray();
var_dump($rombelList);
