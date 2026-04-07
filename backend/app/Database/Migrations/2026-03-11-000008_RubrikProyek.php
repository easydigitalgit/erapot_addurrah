<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RubrikProyek extends Migration
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
            'proyek_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nama_aspek' => [ // Menggantikan dimensi, elemen, dan sub_elemen
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'bobot' => [ // Kolom baru untuk penentuan persentase nilai
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proyek_id'); // Index untuk mempercepat relasi ke tabel proyek
        
        $this->forge->createTable('rubrik_proyek');
    }

    public function down()
    {
        $this->forge->dropTable('rubrik_proyek');
    }
}