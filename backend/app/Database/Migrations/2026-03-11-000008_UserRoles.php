<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Ditambahkan agar cocok dengan tabel users
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Ditambahkan agar cocok dengan tabel roles
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // Composite index sangat penting di sini untuk performa pengecekan auth/role
        $this->forge->addKey(['user_id', 'role_id']); 
        
        $this->forge->createTable('user_roles');
    }

    public function down()
    {
        $this->forge->dropTable('user_roles');
    }
}