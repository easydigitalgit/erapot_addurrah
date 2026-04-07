<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class TargetTahfidz extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['VII', 'VIII', 'IX']
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap']
            ],
            'juz_id' => [
                'type'       => 'INT',
                'constraint' => 11
            ],
            'surah_mulai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'surah_sampai_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'minimal_hafalan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0, // Disesuaikan dari 100 ke 0
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Nonaktif'],
                'default'    => 'Aktif',
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
        $this->forge->createTable('target_tahfidz');
    }

    public function down()
    {
        $this->forge->dropTable('target_tahfidz');
    }
}