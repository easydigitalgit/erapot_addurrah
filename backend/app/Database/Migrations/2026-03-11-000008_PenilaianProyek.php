<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class PenilaianProyek extends Migration
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
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nama_proyek' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['Individu', 'Kelompok'],
            ],
            'tanggal_pelaksanaan' => [
                'type' => 'DATE',
            ],
            'kkm' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 75,
            ],
            'deskripsi' => [ // Menggantikan catatan
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Draft',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk mempercepat pencarian proyek berdasarkan guru atau rombel
        $this->forge->addKey(['guru_id', 'rombel_id', 'mapel_id']);
        
        $this->forge->createTable('penilaian_proyek');
    }

    public function down()
    {
        $this->forge->dropTable('penilaian_proyek');
    }
}