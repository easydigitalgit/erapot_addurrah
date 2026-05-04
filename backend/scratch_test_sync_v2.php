<?php

$host = 'localhost';
$user = 'dev';
$pass = '12345678';
$db   = 'raporsmpit';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dummyName = 'TEST STUDENT SYNC ' . rand(100, 999);
$dummyRombel = 14; // Crystal

echo "Testing synchronization for student: $dummyName\n";

// 1. Get active TA
$res = $conn->query("SELECT id, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1");
$taAktif = $res->fetch_assoc();

if (!$taAktif) {
    die("Error: No active Tahun Ajaran found.\n");
}
echo "Active TA: {$taAktif['tahun']} {$taAktif['semester']} (ID: {$taAktif['id']})\n";

// 2. Simulate logic
$conn->begin_transaction();

$nis = '99.99.' . rand(10000, 99999);
$stmt = $conn->prepare("INSERT INTO siswa (nama_lengkap, rombel_id, nis) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $dummyName, $dummyRombel, $nis);
$stmt->execute();
$siswaId = $conn->insert_id;

echo "Student created with ID: $siswaId\n";

// Logika Fix
if ($dummyRombel) {
    $stmt2 = $conn->prepare("INSERT INTO anggota_rombel (siswa_id, rombel_id, tahun_ajaran_id, semester) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiis", $siswaId, $dummyRombel, $taAktif['id'], $taAktif['semester']);
    $stmt2->execute();
    echo "Inserted into anggota_rombel.\n";
}

$conn->commit();

// Verification
$res2 = $conn->query("SELECT * FROM anggota_rombel WHERE siswa_id = $siswaId");
if ($res2->num_rows > 0) {
    echo "VERIFICATION PASSED: Student found in anggota_rombel.\n";
} else {
    echo "VERIFICATION FAILED: Student NOT found in anggota_rombel.\n";
}

// Cleanup
$conn->query("DELETE FROM anggota_rombel WHERE siswa_id = $siswaId");
$conn->query("DELETE FROM siswa WHERE id = $siswaId");
echo "Cleanup done.\n";

$conn->close();
