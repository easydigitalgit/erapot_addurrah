<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefJuz extends Migration
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
            'nama_juz' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'mulai_surah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                // 'unsigned' => true, // Opsional: Tambahkan jika relasi ke ID surah juga unsigned
            ],
            'sampai_surah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                // 'unsigned' => true, // Opsional: Tambahkan jika relasi ke ID surah juga unsigned
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('ref_juz');
    }

    public function down()
    {
        $this->forge->dropTable('ref_juz');
    }
}