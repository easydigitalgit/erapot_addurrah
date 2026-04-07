<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class BackupSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'auto_backup' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => true,
            ],
            'frequency' => [ // Menggantikan backup_frequency
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'daily',
                'null'       => true,
            ],
            'execution_time' => [ // Menggantikan backup_time
                'type'    => 'TIME',
                'default' => '02:00:00',
                'null'    => true,
            ],
            'retention_days' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 30,
                'null'       => true,
            ],
            'notify_email' => [ // Kolom baru untuk notifikasi status backup
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('backup_settings');
    }

    public function down()
    {
        $this->forge->dropTable('backup_settings');
    }
}