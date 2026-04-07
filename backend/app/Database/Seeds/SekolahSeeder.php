<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SekolahSeeder extends Seeder
{
public function run() {
    $data = [
        'npsn' => '10212345', 'nama_sekolah' => 'SMP XYZ', 'jenjang' => 'SMPIT',
        'status_sekolah' => 'Swasta', 'alamat' => 'Jl. Pendidikan No 1', 'akreditasi' => 'A',
        'tahun_berdiri' => '2010', 'warna_primary' => '#1F7A4D', 'warna_secondary' => '#E6F4EC'
    ];
    $this->db->table('sekolah')->truncate();
    $this->db->table('sekolah')->insert($data);
}}
