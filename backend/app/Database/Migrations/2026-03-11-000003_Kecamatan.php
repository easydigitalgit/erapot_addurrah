<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kecamatan extends Migration
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
                'constraint' => 8,
            ],
            'propinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'prop_id' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => true,
            ],
            'prop_ktp' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => true,
            ],
            'kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'kab_id' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => true,
            ],
            'kab_ktp' => [
                'type'       => 'CHAR',
                'constraint' => 6,
                'null'       => true,
            ],
            'kab_kode' => [
                'type'       => 'CHAR',
                'constraint' => 8,
                'null'       => false, // Di target DB tertulis 'No' untuk Null
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 35,
                'null'       => true,
            ],
            'kec_id' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => true,
            ],
            'kec_ktp' => [
                'type'       => 'CHAR',
                'constraint' => 6,
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
        $this->forge->createTable('kecamatan');
    }

    public function down()
    {
        $this->forge->dropTable('kecamatan');
    }
}