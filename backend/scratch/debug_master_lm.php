<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "Checking Master LM...\n";

// Get IPA Mapel ID
$res = $mysqli->query("SELECT id, nama_mapel FROM mata_pelajaran WHERE nama_mapel LIKE '%Alam%'");
$mapels = [];
while ($row = $res->fetch_assoc()) {
    $mapels[] = $row;
}

if (empty($mapels)) {
    echo "Mata Pelajaran 'Alam' not found.\n";
} else {
    foreach ($mapels as $mapel) {
        echo "\nMapel: " . $mapel['nama_mapel'] . " (ID: " . $mapel['id'] . ")\n";
        
        $sql = "SELECT tingkat, semester, kategori, status, tahun_ajaran_id, COUNT(*) as count 
                FROM master_lm 
                WHERE mapel_id = {$mapel['id']} 
                GROUP BY tingkat, semester, kategori, status, tahun_ajaran_id";
        $res2 = $mysqli->query($sql);
        if ($res2 && $res2->num_rows > 0) {
            echo "Found LM entries:\n";
            while ($row = $res2->fetch_assoc()) {
                echo "- Tingkat: {$row['tingkat']} | Sem: {$row['semester']} | Kat: {$row['kategori']} | Stat: {$row['status']} | TA_ID: {$row['tahun_ajaran_id']} | Count: {$row['count']}\n";
            }
        } else {
            echo "No LM entries found for this mapel.\n";
        }
    }
}

$mysqli->close();
