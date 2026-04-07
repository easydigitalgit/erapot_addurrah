<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class NilaiTahfidz extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun_ajaran_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'comment'    => 'ID dari tabel tahun_ajaran',
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap'],
                'default'    => 'Ganjil',
            ],
            'predikat' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'Misal: Sangat Baik / A',
            ],
            'deskripsi' => [ // Menggantikan catatan
                'type'       => 'TEXT',
                'comment'    => 'Catatan narasi untuk di rapor',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk optimasi pencarian nilai rapor per siswa
        $this->forge->addKey(['siswa_id', 'tahun_ajaran_id', 'semester']);
        
        $this->forge->createTable('nilai_tahfidz');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_tahfidz');
    }
}