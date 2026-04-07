<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SettingBobotNilai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori' => ['type' => 'VARCHAR', 'constraint' => 50],
            'bobot' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('setting_bobot_nilai');
    }

    public function down() { $this->forge->dropTable('setting_bobot_nilai'); }
}