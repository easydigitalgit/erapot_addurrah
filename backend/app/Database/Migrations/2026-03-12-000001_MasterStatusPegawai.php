<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterStatusPegawai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
        ]);

        // Menjadikan kolom 'id' sebagai Primary Key
        $this->forge->addKey('id', true);

        // Membuat tabel
        $this->forge->createTable('master_status_pegawai');
    }

    public function down()
    {
        // Menghapus tabel jika migrasi di-rollback
        $this->forge->dropTable('master_status_pegawai');
    }
}