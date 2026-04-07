<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SettingAturanNilai extends Migration
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
            'predikat' => [
                'type'       => 'VARCHAR',
                'constraint' => 5, // Contoh: A, B, C+, atau D
            ],
            'deskripsi_predikat' => [
                'type'       => 'VARCHAR',
                'constraint' => 100, // Contoh: Sangat Baik, Baik, Cukup
            ],
            'nilai_min' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nilai_max' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'deskripsi_kompetensi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'warna_badge' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'emerald', // Sesuai default di database kamu
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('setting_aturan_nilai');
    }

    public function down()
    {
        $this->forge->dropTable('setting_aturan_nilai');
    }
}