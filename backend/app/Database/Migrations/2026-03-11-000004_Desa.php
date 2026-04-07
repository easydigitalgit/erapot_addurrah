<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Desa extends Migration
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
                'constraint' => 13,
                'null'       => true,
            ],
            'propinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'prop_id' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => false, // Database target: No Null
            ],
            'kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'kab_id' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => false, // Database target: No Null
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 31,
                'null'       => true,
            ],
            'kec_id' => [
                'type'       => 'CHAR',
                'constraint' => 3, // Disesuaikan ke 3
                'null'       => false, // Database target: No Null
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'desa_id' => [
                'type'       => 'CHAR',
                'constraint' => 4,
                'null'       => false,
            ],
            'status_adm' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            // Kolom Koordinat Desa
            'koordinat' => ['type' => 'TEXT', 'null' => true],
            'latitude'  => ['type' => 'TEXT', 'null' => true],
            'longitude' => ['type' => 'TEXT', 'null' => true],
            // Kolom Koordinat Kecamatan
            'lat_kecamatan'   => ['type' => 'TEXT', 'null' => true],
            'long_kecamatan'  => ['type' => 'TEXT', 'null' => true],
            'koord_kecamatan' => ['type' => 'TEXT', 'null' => true],
            // Kolom Koordinat Kabupaten
            'lat_kabupaten'   => ['type' => 'TEXT', 'null' => true],
            'long_kabupaten'  => ['type' => 'TEXT', 'null' => true],
            'koord_kabupaten' => ['type' => 'TEXT', 'null' => true],
            // Kolom Koordinat Propinsi
            'lat_propinsi'   => ['type' => 'TEXT', 'null' => true],
            'long_propinsi'  => ['type' => 'TEXT', 'null' => true],
            'koord_propinsi' => ['type' => 'TEXT', 'null' => true],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Aktif',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kode'); 
        $this->forge->createTable('desa');
    }

    public function down()
    {
        $this->forge->dropTable('desa');
    }
}