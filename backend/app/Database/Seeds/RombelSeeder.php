<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RombelSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_rombel' => 'VII-A', 'tingkat' => 'VII',  'kurikulum' => 'Kurikulum Merdeka', 'id_tahun_ajaran' => 2, 'wali_kelas_id' => 1, 'semester' => 'Genap'],
            ['nama_rombel' => 'VII-B', 'tingkat' => 'VII',  'kurikulum' => 'Kurikulum Merdeka', 'id_tahun_ajaran' => 2, 'wali_kelas_id' => 2, 'semester' => 'Genap'],
            ['nama_rombel' => 'VIII-A', 'tingkat' => 'VIII', 'kurikulum' => 'Kurikulum Merdeka', 'id_tahun_ajaran' => 2, 'wali_kelas_id' => 3, 'semester' => 'Genap'],
            ['nama_rombel' => 'IX-A',  'tingkat' => 'IX',   'kurikulum' => 'Kurikulum Merdeka', 'id_tahun_ajaran' => 2, 'wali_kelas_id' => 4, 'semester' => 'Genap'],
        ];

        $this->db->table('rombel')->truncate();
        $this->db->table('rombel')->insertBatch($data);

        echo "Data Rombel (Kelas) berhasil disemai!\n";
    }
}