<?php
$m = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$sql = file_get_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql');

// Extract the VALUES part of INSERT INTO siswa
preg_match('/INSERT INTO siswa [^V]+VALUES\s*(.+?)\s*ON DUPLICATE KEY/s', $sql, $mSiswa);
$rows = explode("),\n(", trim($mSiswa[1], "(); \n\r\t"));

echo "Testing " . count($rows) . " rows for INSERT INTO siswa individually...\n";

foreach ($rows as $idx => $r) {
    $rowSql = "(" . trim($r, "()") . ")";
    $testQ = "INSERT INTO siswa (id, rombel_id, nis, nisn, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, alamat_siswa, dusun, kelurahan, kecamatan, kode_pos, jenis_tinggal, alat_transportasi, no_hp, asal_sekolah, status_siswa, anak_ke, jml_saudara_kandung) VALUES $rowSql ON DUPLICATE KEY UPDATE rombel_id=VALUES(rombel_id)";
    
    // Test on a temporary or dummy check
    preg_match("/\((\d+),\s*(\d+),\s*'([^']*)',\s*'([^']*)'/", $rowSql, $cols);
    $id = $cols[1];
    $rombel = $cols[2];
    $nis = $cols[3];
    $nisn = $cols[4];

    // Check duplicate NISN
    if ($nisn) {
        $c = $m->query("SELECT id, nama_lengkap FROM siswa WHERE nisn = '$nisn' AND id != $id")->fetch_all(MYSQLI_ASSOC);
        if (!empty($c)) {
            echo "❌ ERROR on Row $idx (ID $id, $nisn): Duplicate NISN with ID {$c[0]['id']} ({$c[0]['nama_lengkap']})\n";
        }
    }
}
echo "Done testing all rows!\n";
