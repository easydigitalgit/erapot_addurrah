<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class LoginLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false, // Di gambar tidak ada atribut unsigned
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false, // Sesuai gambar: Null = No
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT', // Sesuai gambar pakai TEXT, bukan VARCHAR
                'null' => true,
            ],
            'device_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'browser_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => 'Normal',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false, // Sesuai gambar: Null = No
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true); // Primary Key
        $this->forge->addKey('user_id');  // Index biasa sesuai gambar (ikon kunci abu-abu)
        
        $this->forge->createTable('login_logs');
    }

    public function down() 
    { 
        $this->forge->dropTable('login_logs'); 
    }
}