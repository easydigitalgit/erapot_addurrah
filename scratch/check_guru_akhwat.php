<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$res = $pdo->query("SELECT gm.*, g.nama_lengkap as nama_guru, r.nama_rombel, m.nama_mapel 
                    FROM guru_mapel gm 
                    JOIN guru_tendik g ON g.id = gm.guru_id 
                    JOIN rombel r ON r.id = gm.rombel_id 
                    JOIN mata_pelajaran m ON m.id = gm.mapel_id 
                    WHERE gm.rombel_id IN (80, 81) AND gm.mapel_id IN (10, 44)")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

