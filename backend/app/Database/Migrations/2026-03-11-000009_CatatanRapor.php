<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CatatanRapor extends Migration
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
            'kata_pengantar' => [ // Kolom baru untuk pembuka rapor
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatan_wali_kelas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_kenaikan' => [ // Kolom baru (Naik/Tidak Naik/Lulus)
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
        $this->forge->addKey(['siswa_id', 'tahun_ajaran', 'semester']);
        
        $this->forge->createTable('catatan_rapor');
    }

    public function down()
    {
        $this->forge->dropTable('catatan_rapor');
    }
}