<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Rombel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => false, // Sesuai screenshot
                'auto_increment' => true,
            ],
            'nama_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['VII', 'VIII', 'IX'],
            ],
            'kurikulum' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Kurikulum Merdeka',
                'null'       => true,
            ],
            'id_tahun_ajaran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'wali_kelas_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap'],
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // Di screenshot, hanya wali_kelas_id yang punya index (icon kunci abu-abu)
        $this->forge->addKey('wali_kelas_id'); 
        
        $this->forge->createTable('rombel');
    }

    public function down()
    {
        $this->forge->dropTable('rombel');
    }
}