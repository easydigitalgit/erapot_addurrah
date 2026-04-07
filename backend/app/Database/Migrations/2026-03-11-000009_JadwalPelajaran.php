<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class JadwalPelajaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Bisa NULL jika TAHFIZH/BPI',
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'kode_jadwal_excel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Menyimpan kode asli: IPS/AAM, dll',
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'jam_ke' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'Les 1, 2, 3 atau ISTIRAHAT',
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'jam_selesai' => [
                'type' => 'TIME',
            ],
            'jenis_jadwal' => [
                'type'       => 'ENUM',
                'constraint' => ['Reguler', 'Jumat BPI', 'Jumat Non-BPI'],
                'default'    => 'Reguler',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['rombel_id', 'hari']); // Index untuk mempermudah pemuatan jadwal per kelas
        
        $this->forge->createTable('jadwal_pelajaran');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal_pelajaran');
    }
}