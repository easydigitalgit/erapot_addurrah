<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run() 
    {
        $data = [
            ['nuptk' => '19850101', 'nama_lengkap' => 'Ahmad Fauzi, S.Pd',   'jenis_kelamin' => 'L', 'status_kepegawaian' => 'GTY'],
            ['nuptk' => '19880202', 'nama_lengkap' => 'Siti Aminah, M.Pd',   'jenis_kelamin' => 'P', 'status_kepegawaian' => 'GTY'],
            ['nuptk' => '19900303', 'nama_lengkap' => 'Budi Santoso, S.Kom', 'jenis_kelamin' => 'L', 'status_kepegawaian' => 'GTT'],
            ['nuptk' => '19920404', 'nama_lengkap' => 'Nurul Hidayah, S.Ag', 'jenis_kelamin' => 'P', 'status_kepegawaian' => 'GTY'],
        ];

        $this->db->table('guru_tendik')->truncate();
        $this->db->table('guru_tendik')->insertBatch($data);    
        
        echo "Data Guru & Tendik berhasil disemai!\n";
    }
}