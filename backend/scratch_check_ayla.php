<?php
require 'app/Config/Database.php';
$db = \Config\Database::connect();

$name = 'Ayla Shaufa';
$siswa = $db->table('siswa')->like('nama_lengkap', $name)->get()->getResultArray();

echo "STUDENT DATA:\n";
print_r($siswa);

if (!empty($siswa)) {
    $siswaId = $siswa[0]['id'];
    $anggota = $db->table('anggota_rombel')->where('siswa_id', $siswaId)->get()->getResultArray();
    echo "\nANGGOTA ROMBEL DATA:\n";
    print_r($anggota);
    
    foreach ($anggota as $ar) {
        $rombel = $db->table('rombel')->where('id', $ar['rombel_id'])->get()->getRowArray();
        echo "\nRombel ID " . $ar['rombel_id'] . " is: " . ($rombel['nama_rombel'] ?? 'Unknown') . "\n";
    }
}

$ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
echo "\nTA AKTIF:\n";
print_r($ta);
