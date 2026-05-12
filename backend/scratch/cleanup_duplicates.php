<?php
/**
 * SCRIPT PEMBERSIHAN DATA NILAI FORMATIF GANDA
 * 
 * Script ini akan menghapus record di tabel 'nilai_formatif' yang memiliki 
 * kategori kosong/NULL, jika ditemukan record lain yang identik (siswa, mapel, rombel, pertemuan, TA) 
 * namun memiliki kategori yang valid ('Tengah Semester' atau 'Akhir Semester').
 */

$db_config = [
    'host' => 'localhost',
    'user' => 'dev',
    'pass' => '12345678',
    'name' => 'raporsmpit'
];

$conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Memulai proses pembersihan data duplikat...\n";

// 1. Identifikasi duplikat
$sql_check = "
    SELECT 
        siswa_id, mapel_id, rombel_id, tahun_ajaran_id, semester, jenis_penilaian, pertemuan,
        COUNT(*) as jumlah
    FROM nilai_formatif
    GROUP BY siswa_id, mapel_id, rombel_id, tahun_ajaran_id, semester, jenis_penilaian, pertemuan
    HAVING jumlah > 1
";

$res = $conn->query($sql_check);
echo "Ditemukan " . $res->num_rows . " kombinasi data yang terindikasi duplikat.\n";

// 2. Jalankan Query Penghapusan
$sql_delete = "
    DELETE n1 FROM nilai_formatif n1
    INNER JOIN nilai_formatif n2 ON 
        n1.siswa_id = n2.siswa_id AND
        n1.mapel_id = n2.mapel_id AND
        n1.rombel_id = n2.rombel_id AND
        n1.tahun_ajaran_id = n2.tahun_ajaran_id AND
        n1.semester = n2.semester AND
        n1.jenis_penilaian = n2.jenis_penilaian AND
        n1.pertemuan = n2.pertemuan
    WHERE 
        (n1.kategori IS NULL OR n1.kategori = '') AND 
        (n2.kategori IS NOT NULL AND n2.kategori != '') AND
        n1.id != n2.id
";

if ($conn->query($sql_delete)) {
    echo "Pembersihan berhasil. Baris terhapus: " . $conn->affected_rows . "\n";
} else {
    echo "Gagal menjalankan query pembersihan: " . $conn->error . "\n";
}

// 3. Update juga jika ada kategori yang masih kosong tapi tidak ada duplikat (opsional, tapi disarankan)
// Untuk saat ini kita fokus ke duplikat dulu.

$conn->close();
echo "Selesai.\n";
