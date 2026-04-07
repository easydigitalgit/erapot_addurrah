<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        $data = [
            ['id' => 1, 'role_name' => 'Super Admin',   'description' => 'Administrator Sistem', 'status' => 'Aktif', 'created_at' => $now],
            ['id' => 2, 'role_name' => 'Guru',          'description' => 'Guru Mata Pelajaran',  'status' => 'Aktif', 'created_at' => $now],
            ['id' => 3, 'role_name' => 'Wali Kelas',    'description' => 'Wali Kelas',           'status' => 'Aktif', 'created_at' => $now],
            ['id' => 4, 'role_name' => 'Siswa',         'description' => 'Peserta Didik',        'status' => 'Aktif', 'created_at' => $now],
            ['id' => 5, 'role_name' => 'Orang Tua',     'description' => 'Orang Tua / Wali',     'status' => 'Aktif', 'created_at' => $now],
            ['id' => 6, 'role_name' => 'Guru Tahfidzh', 'description' => 'Pengampu Tahfidz',     'status' => 'Aktif', 'created_at' => $now],
        ];

        $this->db->table('roles')->truncate();
        $this->db->table('roles')->insertBatch($data);
        
        echo "Data Roles berhasil disemai!\n";
    }
}