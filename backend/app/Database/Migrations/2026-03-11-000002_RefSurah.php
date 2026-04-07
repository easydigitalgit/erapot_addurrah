<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefSurah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'no_surah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nama_surah' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nama_surah_arab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true, // Di database tertulis 'Yes' untuk Null
                'collation'  => 'utf8mb4_unicode_ci', // Tetap gunakan utf8mb4 agar support karakter Arab modern
            ],
            'arti_surah' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,   // Disesuaikan dari 255 ke 100
                'null'       => true,   // Di database tertulis 'Yes' untuk Null
            ],
            'jumlah_ayat' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('ref_surah');
    }

    public function down()
    {
        $this->forge->dropTable('ref_surah');
    }
}