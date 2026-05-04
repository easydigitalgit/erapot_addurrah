<?php
/**
 * Script Sinkronisasi Mesin Waktu (anggota_rombel)
 * Menyamakan data anggota_rombel dengan rombel_id aktual di tabel siswa.
 */

$mysqli = new mysqli("localhost", "dev", "12345678", "raporsmpit");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Cari Tahun Ajaran Aktif
$resTA = $mysqli->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1");
$taAktif = $resTA->fetch_assoc();

if (!$taAktif) {
    die("Tidak ada Tahun Ajaran aktif.");
}

$taId = $taAktif['id'];
$semester = $taAktif['semester'];

echo "Tahun Ajaran Aktif: " . $taAktif['tahun'] . " ($semester)\n";

// 2. Ambil semua siswa yang punya rombel_id tapi tidak sinkron dengan anggota_rombel
$query = "
    SELECT s.id as siswa_id, s.nama_lengkap, s.rombel_id as rombel_aktual, ar.id as ar_id, ar.rombel_id as ar_rombel_id
    FROM siswa s
    LEFT JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = $taId
    WHERE s.rombel_id IS NOT NULL AND s.status_siswa = 'Aktif'
";

$resSiswa = $mysqli->query($query);
$countFixed = 0;
$mismatch = [];

while ($row = $resSiswa->fetch_assoc()) {
    $siswaId = $row['siswa_id'];
    $nama = $row['nama_lengkap'];
    $rombelSiswa = $row['rombel_aktual'];
    $arId = $row['ar_id'];
    $rombelAR = $row['ar_rombel_id'];

    if (!$arId) {
        // Belum ada record di anggota_rombel, buat baru
        echo "[NEW] $nama -> Rombel $rombelSiswa\n";
        $stmt = $mysqli->prepare("INSERT INTO anggota_rombel (siswa_id, rombel_id, tahun_ajaran_id, semester) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $siswaId, $rombelSiswa, $taId, $semester);
        $stmt->execute();
        $countFixed++;
    } elseif ($rombelSiswa != $rombelAR) {
        // Ada tapi beda rombel, update
        echo "[UPDATE] $nama: $rombelAR -> $rombelSiswa\n";
        $stmt = $mysqli->prepare("UPDATE anggota_rombel SET rombel_id = ?, semester = ? WHERE id = ?");
        $stmt->bind_param("isi", $rombelSiswa, $semester, $arId);
        $stmt->execute();
        $countFixed++;
    }
}

echo "\nSelesai! $countFixed data siswa berhasil disinkronkan.\n";
$mysqli->close();
