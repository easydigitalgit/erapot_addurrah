<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserPreferences extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'       => 'INT',
                // Saya tambahkan 'unsigned' => true karena biasanya user_id berelasi 
                // dengan Primary Key tabel users yang auto_increment dan unsigned.
                'unsigned'   => true, 
                'null'       => false,
            ],
            'notif_login' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'two_factor' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'bahasa' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'default'    => 'id',
            ],
            'theme' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'default'    => 'light',
            ],
            'notif_email' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'notif_sistem' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
            'notif_update' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
            ],
        ]);

        // Menjadikan user_id sebagai Primary Key
        $this->forge->addKey('user_id', true);

        // Membuat tabel
        $this->forge->createTable('user_preferences');
    }

    public function down()
    {
        // Menghapus tabel jika migrasi di-rollback
        $this->forge->dropTable('user_preferences');
    }
}