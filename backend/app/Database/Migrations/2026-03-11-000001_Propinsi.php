<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Propinsi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true, // Standar untuk ID Auto Increment
                'auto_increment' => true,
            ],
            'kode' => [
                'type'       => 'CHAR',
                'constraint' => 2,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
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

        $this->forge->addKey('id', true);   // Primary Key
        $this->forge->addKey('kode');       // Index tambahan untuk kode wilayah
        $this->forge->createTable('propinsi');
    }

    public function down()
    {
        $this->forge->dropTable('propinsi');
    }
}