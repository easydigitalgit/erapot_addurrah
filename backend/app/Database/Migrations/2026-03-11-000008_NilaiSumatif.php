<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class NilaiSumatif extends Migration
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
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jenis_sumatif' => [
                'type'       => 'ENUM',
                'constraint' => ['pts', 'pas', 'sas'],
            ],
            'nilai' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => true,
            ],
            'deskripsi' => [ // Sebelumnya 'catatan'
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [ // Kolom baru untuk alur validasi nilai
                'type'       => 'ENUM',
                'constraint' => ['draft', 'siap_validasi', 'terkunci'],
                'default'    => 'draft',
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
        // Index untuk mempercepat pencarian nilai per siswa dan mapel
        $this->forge->addKey(['siswa_id', 'mapel_id']); 
        
        $this->forge->createTable('nilai_sumatif');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_sumatif');
    }
}