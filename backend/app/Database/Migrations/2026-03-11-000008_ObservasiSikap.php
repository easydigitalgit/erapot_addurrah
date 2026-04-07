<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ObservasiSikap extends Migration
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
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'guru_id' => [ // Kolom baru: Siapa yang melakukan observasi
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mapel_id' => [ // Kolom baru: Dilakukan di jam pelajaran apa
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'rombel_id' => [ // Kolom baru: Kelas siswa saat itu
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'parameter_sikap' => [ // Contoh: "Tanggung Jawab", "Kejujuran", "Spiritual"
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'skala' => [ // Contoh: "Sangat Baik", "Mulai Berkembang", atau "A"
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => false, // Di database target tertulis 'No' untuk Null
            ],
            'tanggal' => [ // Tanggal kejadian/observasi
                'type' => 'DATE',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk mempermudah pencarian jurnal sikap per siswa dan per tanggal
        $this->forge->addKey(['siswa_id', 'tanggal']);
        
        $this->forge->createTable('observasi_sikap');
    }

    public function down()
    {
        $this->forge->dropTable('observasi_sikap');
    }
}