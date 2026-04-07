<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kabupaten extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode' => [
                'type'       => 'CHAR',
                'constraint' => 5,
                'null'       => true, // Sesuai target DB (Yes)
            ],
            'propinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,   // Disesuaikan ke 30
                'null'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,   // Disesuaikan ke 30
                'null'       => true,
            ],
            'koordinat' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
            'latitude' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kode'); 
        $this->forge->createTable('kabupaten');
    }

    public function down()
    {
        $this->forge->dropTable('kabupaten');
    }
}