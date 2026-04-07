<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ValidasiRaporSiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap'],
                'null'       => false,
            ],
            'is_locked' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 1, // Sesuai gambar, defaultnya adalah 1
            ],
            'locked_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'locked_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        // Menjadikan id sebagai Primary Key
        $this->forge->addKey('id', true);

        // Membuat tabel
        $this->forge->createTable('validasi_rapor_siswa');
    }

    public function down()
    {
        // Menghapus tabel jika rollback
        $this->forge->dropTable('validasi_rapor_siswa');
    }
}