<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AuditLogs extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'comment'    => 'ID Admin yang melakukan aksi',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'Jenis Aksi (misal: UPDATE_PERMISSION)',
            ],
            'description' => [ // Menggantikan old_data, new_data, table_name, record_id
                'type'    => 'TEXT',
                'comment' => 'Detail perubahan',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'comment'    => 'Alamat IP pengguna',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id'); // Mempercepat filter log per admin
        
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}