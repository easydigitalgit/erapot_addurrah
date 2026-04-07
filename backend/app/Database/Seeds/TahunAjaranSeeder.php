<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['tahun' => '2024/2025', 'semester' => 'Ganjil', 'tgl_mulai' => '2024-07-15', 'tgl_akhir' => '2024-12-20', 'status' => 'Arsip', 'is_locked' => 1],
            ['tahun' => '2024/2025', 'semester' => 'Genap',  'tgl_mulai' => '2025-01-06', 'tgl_akhir' => '2025-06-20', 'status' => 'Aktif', 'is_locked' => 0],
            ['tahun' => '2025/2026', 'semester' => 'Ganjil', 'tgl_mulai' => '2025-07-14', 'tgl_akhir' => '2025-12-19', 'status' => 'Arsip', 'is_locked' => 0],
            ['tahun' => '2025/2026', 'semester' => 'Genap',  'tgl_mulai' => '2026-01-05', 'tgl_akhir' => '2026-06-19', 'status' => 'Arsip', 'is_locked' => 0],
        ];

        $this->db->table('tahun_ajaran')->truncate();
        $this->db->table('tahun_ajaran')->insertBatch($data);
        
        echo "Data Tahun Ajaran berhasil disemai!\n";
    }
}