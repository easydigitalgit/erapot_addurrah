<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kurikulum extends Migration
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
            'nama_kurikulum' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['Merdeka', 'K13', 'Lainnya'],
            ],
            'tahun_berlaku' => [
                'type'       => 'YEAR',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Non-aktif'],
                'default'    => 'Aktif', // Sesuai dengan screenshot database kamu
                'null'       => true,    // Screenshot menunjukkan status boleh NULL (Yes)
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kurikulum');
    }

    public function down()
    {
        $this->forge->dropTable('kurikulum');
    }
}