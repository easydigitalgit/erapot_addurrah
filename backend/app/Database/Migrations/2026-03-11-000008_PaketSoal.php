<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class PaketSoal extends Migration
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
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tingkat' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'nama_paket' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'kelas_target' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Contoh: Kelas 8A, Kelas 8B',
            ],
            'kumpulan_soal_id' => [
                'type'    => 'TEXT',
                'comment' => 'Menyimpan array ID soal dari bank_soal',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Aktif',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('paket_soal');
    }

    public function down()
    {
        $this->forge->dropTable('paket_soal');
    }
}