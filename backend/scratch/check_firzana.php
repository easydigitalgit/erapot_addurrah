<?php
$mysqli = new mysqli('localhost', 'dev', '12345678', 'raporsmpit');
$u = $mysqli->query("SELECT * FROM users WHERE id = 240")->fetch_assoc();
echo "User 240: \n";
print_r($u);

// Check if any orangtua_wali has user_id = 240
$ow = $mysqli->query("SELECT * FROM orangtua_wali WHERE user_id = 240")->fetch_assoc();
echo "Orangtua wali by user_id 240: \n";
print_r($ow);

// Check if any orangtua_wali has nik or no_kk or similar
// Check total rows in siswa vs orangtua_wali
$c_siswa = $mysqli->query("SELECT count(*) FROM siswa")->fetch_row()[0];
$c_ortu = $mysqli->query("SELECT count(*) FROM orangtua_wali")->fetch_row()[0];
echo "Total siswa: $c_siswa | Total orangtua_wali: $c_ortu\n";

// Check which siswa don't have orangtua_wali
$missing = $mysqli->query("
    SELECT s.id, s.nis, s.nisn, s.nama_lengkap, s.rombel_id, r.nama_rombel 
    FROM siswa s 
    LEFT JOIN orangtua_wali ow ON s.id = ow.siswa_id 
    LEFT JOIN rombel r ON s.rombel_id = r.id
    WHERE ow.id IS NULL
");
echo "Jumlah siswa tanpa orangtua_wali: " . $missing->num_rows . "\n";
while ($m = $missing->fetch_assoc()) {
    echo "- ID: {$m['id']}, Rombel: {$m['nama_rombel']}, NIS: {$m['nis']}, Nama: {$m['nama_lengkap']}\n";
}
