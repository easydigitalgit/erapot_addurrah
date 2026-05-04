<?php

// Load CI4 environment
require 'd:/xampp/htdocs/erapoteasy/backend/vendor/autoload.php';
require 'd:/xampp/htdocs/erapoteasy/backend/system/Test/bootstrap.php';

$db = \Config\Database::connect();

$dummyName = 'TEST STUDENT SYNC ' . rand(100, 999);
$dummyRombel = 14; // Crystal

echo "Testing synchronization for student: $dummyName\n";

// 1. Check active TA
$taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
if (!$taAktif) {
    die("Error: No active Tahun Ajaran found.\n");
}
echo "Active TA: {$taAktif['tahun']} {$taAktif['semester']} (ID: {$taAktif['id']})\n";

// 2. Simulate store() logic
$db->transStart();

$siswaData = [
    'nama_lengkap' => $dummyName,
    'rombel_id'    => $dummyRombel,
    'nis'          => '99.99.' . rand(10000, 99999)
];

$db->table('siswa')->insert($siswaData);
$siswaId = $db->insertID();

echo "Student created with ID: $siswaId\n";

// --- START OF LOGIKA FIX ---
if (!empty($siswaData['rombel_id'])) {
    if ($taAktif) {
        $db->table('anggota_rombel')->insert([
            'siswa_id'        => $siswaId,
            'rombel_id'       => $siswaData['rombel_id'],
            'tahun_ajaran_id' => $taAktif['id'],
            'semester'        => $taAktif['semester']
        ]);
        echo "Inserted into anggota_rombel.\n";
    }
}
// --- END OF LOGIKA FIX ---

$db->transComplete();

if ($db->transStatus() === false) {
    echo "Transaction Failed!\n";
} else {
    echo "Transaction Success!\n";
    
    // Verification
    $check = $db->table('anggota_rombel')
        ->where('siswa_id', $siswaId)
        ->where('rombel_id', $dummyRombel)
        ->where('tahun_ajaran_id', $taAktif['id'])
        ->get()->getRowArray();
        
    if ($check) {
        echo "VERIFICATION PASSED: Student found in anggota_rombel.\n";
    } else {
        echo "VERIFICATION FAILED: Student NOT found in anggota_rombel.\n";
    }
    
    // Cleanup
    $db->table('anggota_rombel')->where('siswa_id', $siswaId)->delete();
    $db->table('siswa')->where('id', $siswaId)->delete();
    echo "Cleanup done.\n";
}
