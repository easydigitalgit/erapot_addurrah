<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHadirToRekapAbsensi extends Migration
{
    public function up()
    {
        $fields = [
            'hadir' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'tahun_ajaran_id'
            ],
            'semester' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'hadir'
            ]
        ];
        $this->forge->addColumn('rekap_absensi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('rekap_absensi', ['hadir', 'semester']);
    }
}
