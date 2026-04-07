<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AbsensiHarian extends Migration
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
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Sakit', 'Izin', 'Alpha', 'Libur'],
                'default'    => 'Hadir'
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Alasan sakit/izin'
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
        $this->forge->addKey(['siswa_id', 'tanggal']); // Index untuk optimasi pencarian histori
        $this->forge->createTable('absensi_harian');
    }

    public function down()
    {
        $this->forge->dropTable('absensi_harian');
    }
}