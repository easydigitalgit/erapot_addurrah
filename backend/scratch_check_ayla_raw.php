<?php
$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$name = "Ayla Shaufa";
$sql = "SELECT id, nama_lengkap, rombel_id FROM siswa WHERE nama_lengkap LIKE '%$name%'";
$result = $mysqli->query($sql);

echo "STUDENT DATA:\n";
while($row = $result->fetch_assoc()) {
    print_r($row);
    $siswaId = $row['id'];
    
    $sql2 = "SELECT * FROM anggota_rombel WHERE siswa_id = $siswaId";
    $result2 = $mysqli->query($sql2);
    echo "\nANGGOTA ROMBEL DATA:\n";
    while($row2 = $result2->fetch_assoc()) {
        print_r($row2);
        $rombelId = $row2['rombel_id'];
        $sql3 = "SELECT nama_rombel FROM rombel WHERE id = $rombelId";
        $result3 = $mysqli->query($sql3);
        $row3 = $result3->fetch_assoc();
        echo "Rombel Name: " . ($row3['nama_rombel'] ?? 'N/A') . "\n";
    }
}

$sql4 = "SELECT * FROM tahun_ajaran WHERE status = 'Aktif'";
$result4 = $mysqli->query($sql4);
echo "\nTA AKTIF:\n";
while($row4 = $result4->fetch_assoc()) {
    print_r($row4);
}
