<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class MateriPembelajaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'guru_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'mapel_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
                'default'    => null,
            ],
            'rombel_ids' => [
                'type'       => 'JSON',
                'null'       => false,
                'comment'    => 'Array ID Kelas',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'tanggal_publikasi' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'published',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                // Gunakan RawSql untuk fungsi bawaan MySQL CURRENT_TIMESTAMP
                'default'    => new RawSql('CURRENT_TIMESTAMP'), 
            ],
        ]);

        // Menjadikan kolom 'id' sebagai Primary Key
        $this->forge->addKey('id', true);

        // Membuat tabel
        $this->forge->createTable('materi_pembelajaran');
    }

    public function down()
    {
        // Menghapus tabel jika migrasi di-rollback
        $this->forge->dropTable('materi_pembelajaran');
    }
}