<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RekapAbsensi extends Migration
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
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun_ajaran' => [ // Berubah dari ID ke VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'semester' => [ // Berubah dari ENUM ke VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'sakit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'izin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'alpha' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk mempermudah penarikan data rekap saat cetak rapor
        $this->forge->addKey(['siswa_id', 'tahun_ajaran', 'semester']);
        
        $this->forge->createTable('rekap_absensi');
    }

    public function down()
    {
        $this->forge->dropTable('rekap_absensi');
    }
}