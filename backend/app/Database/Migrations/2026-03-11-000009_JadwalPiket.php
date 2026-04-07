<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class JadwalPiket extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'shift' => [ // Kolom baru sesuai target
                'type'       => 'ENUM',
                'constraint' => ['Pagi', 'Siang', 'Full'],
                'default'    => 'Pagi',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('guru_id'); 
        
        $this->forge->createTable('jadwal_piket');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal_piket');
    }
}