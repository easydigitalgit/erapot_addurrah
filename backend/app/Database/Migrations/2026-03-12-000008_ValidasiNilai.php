<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ValidasiNilai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rombel_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'progress_akademik' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'default'    => 0.00,
            ],
            'progress_karakter' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'default'    => 0.00,
            ],
            'progress_tahfidz' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'default'    => 0.00,
            ],
            'is_locked' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'locked_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'locked_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Menjadikan id sebagai Primary Key
        $this->forge->addKey('id', true);

        // Menambahkan Unique Index untuk rombel_id (idx_rombel pada gambar)
        $this->forge->addUniqueKey('rombel_id', 'idx_rombel');

        $this->forge->createTable('validasi_nilai');
    }

    public function down()
    {
        $this->forge->dropTable('validasi_nilai');
    }
}