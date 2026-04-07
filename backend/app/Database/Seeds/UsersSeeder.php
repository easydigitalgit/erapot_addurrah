<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'              => 1, 
                'username'        => 'admin',
                'email'           => 'admin@raporsmpit.com',
                'nama_lengkap'    => 'Administrator Utama',
                // Password default: admin123
                'password'        => password_hash('admin123', PASSWORD_DEFAULT),
                'role_id'         => 1, // Mengacu ke ID 1 di tabel roles (Super Admin)
                'is_active'       => 1,
                // created_at dan updated_at tidak perlu diisi manual 
                // karena di tabelmu sudah pakai CURRENT_TIMESTAMP
            ]
        ];

        // Kosongkan tabel agar tidak duplikat jika dieksekusi ulang
        $this->db->table('users')->truncate();
        
        // Insert akun admin
        $this->db->table('users')->insertBatch($data);

        echo "Akun Admin default berhasil dibuat! (Username: admin, Pass: admin123)\n";
    }
}