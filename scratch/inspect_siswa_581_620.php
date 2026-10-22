<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
$res = $mysqli->query("SELECT id, nis, nisn, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, alamat_siswa, kelurahan, kecamatan, kode_pos, diterima_dikelas FROM siswa WHERE id BETWEEN 581 AND 620 ORDER BY id ASC");
while ($r = $res->fetch_assoc()) {
    printf("ID: %3d | NIS: %-15s | NISN: %-25s | Nama: %-10s | Agama: %-18s | Diterima: %-8s\n",
        $r['id'],
        $r['nis'] ?? '',
        $r['nisn'] ?? '',
        $r['nama_lengkap'] ?? '',
        $r['agama'] ?? '',
        $r['diterima_dikelas'] ?? ''
    );
}
