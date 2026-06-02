<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MapelNonaktifRombel extends Migration
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
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('mapel_id');
        $this->forge->addKey('rombel_id');
        $this->forge->createTable('mapel_nonaktif_rombel');
    }

    public function down()
    {
        $this->forge->dropTable('mapel_nonaktif_rombel');
    }
}
