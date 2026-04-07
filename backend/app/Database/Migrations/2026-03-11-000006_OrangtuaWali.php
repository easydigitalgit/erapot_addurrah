<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrangtuaWali extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            // Data Ayah
            'nama_ayah'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun_lahir_ayah'  => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'pendidikan_ayah'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'pekerjaan_ayah'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'penghasilan_ayah'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nik_ayah'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            
            // Data Ibu
            'nama_ibu'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun_lahir_ibu'   => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'pendidikan_ibu'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'pekerjaan_ibu'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'penghasilan_ibu'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nik_ibu'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],

            // Data Wali
            'nama_wali'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun_lahir_wali'  => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'pendidikan_wali'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'pekerjaan_wali'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'penghasilan_wali'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nik_wali'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],

            // Kontak & Alamat
            'email_ortu'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'alamat_orangtua'   => ['type' => 'TEXT', 'null' => true],
            'no_hp_ortu'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('siswa_id');
        $this->forge->addKey('user_id');
        
        $this->forge->createTable('orangtua_wali');
    }

    public function down()
    {
        $this->forge->dropTable('orangtua_wali');
    }
}