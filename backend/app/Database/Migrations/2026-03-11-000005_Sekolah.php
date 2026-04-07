<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Sekolah extends Migration
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
            'nama_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'npsn' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nss' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'jenjang' => [
                'type'       => 'ENUM',
                'constraint' => ['SDIT', 'SMPIT', 'SMAIT'],
                'default'    => 'SMPIT',
                'null'       => true,
            ],
            'status_sekolah' => [ // Nama kolom disesuaikan
                'type'       => 'ENUM',
                'constraint' => ['Negeri', 'Swasta'],
                'default'    => 'Swasta',
                'null'       => true,
            ],
            'tahun_berdiri' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'akreditasi' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'C', 'Belum'],
                'default'    => 'Belum',
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'kode_pos' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'telepon' => [ // Nama kolom disesuaikan
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'website' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'default_logo.png',
                'null'       => true,
            ],
            'warna_primary' => [ // Kolom branding baru
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#1F7A4D',
                'null'       => true,
            ],
            'warna_secondary' => [ // Kolom branding baru
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#E6F4EC',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('sekolah');
    }

    public function down()
    {
        $this->forge->dropTable('sekolah');
    }
}