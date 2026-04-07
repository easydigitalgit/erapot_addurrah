<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NilaiEkskul extends Migration
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
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun_ajaran' => [ // Berubah dari ID ke VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'semester' => [ // Berubah dari ENUM ke VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'nama_kegiatan' => [ // Kolom baru sesuai target
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'predikat' => [ // Berubah dari ENUM ke CHAR
                'type'       => 'CHAR',
                'constraint' => 1,
            ],
            'keterangan' => [ // Nama kolom disesuaikan (sebelumnya deskripsi)
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Menambahkan index pada siswa_id dan tahun_ajaran untuk performa pencarian rapor
        $this->forge->addKey(['siswa_id', 'tahun_ajaran']); 
        
        $this->forge->createTable('nilai_ekskul');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_ekskul');
    }
}