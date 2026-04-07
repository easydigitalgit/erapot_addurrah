<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GuruMapel extends Migration
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
            'user_id' => [ // Relasi ke tabel user (opsional sesuai target)
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'rombel_id' => [ // Kolom krusial untuk menentukan kelas yang diajar
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tahun_ajaran' => [ // Berubah dari ID ke VARCHAR sesuai target
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '2024/2025',
            ],
            'jam_per_minggu' => [ // Beban mengajar guru di kelas tersebut
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 2,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'null'       => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk mempermudah filter jadwal mengajar guru
        $this->forge->addKey(['guru_id', 'mapel_id', 'rombel_id']);
        
        $this->forge->createTable('guru_mapel');
    }

    public function down()
    {
        $this->forge->dropTable('guru_mapel');
    }
}