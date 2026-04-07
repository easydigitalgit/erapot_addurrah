<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MataPelajaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'kode_mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nama_mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kkm' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 75,
            ],
            'kelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'jp_minggu' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'kurikulum_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Non-aktif'],
                'default'    => 'Aktif',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_mapel'); // Sesuai icon kunci unik di gambar
        $this->forge->addKey('kurikulum_id');     // Sesuai icon kunci index di gambar

        $this->forge->createTable('mata_pelajaran');
    }

    public function down()
    {
        $this->forge->dropTable('mata_pelajaran');
    }
}       