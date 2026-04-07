<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RiwayatPerubahanNilai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Admin',
                'null'       => true,
            ],
            'aksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100, // Contoh: "Mengubah Nilai Matematika"
            ],
            'detail' => [
                'type' => 'TEXT', // Tempat menyimpan info lama vs baru
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('riwayat_perubahan_nilai');
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_perubahan_nilai');
    }
}