<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CatatanAkhlak extends Migration
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
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mapel_id' => [ // Opsional: jika kejadian terjadi saat jam pelajaran tertentu
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'rombel_id' => [ // Wajib: mencatat kelas siswa saat kejadian
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kategori_akhlak' => [ // Menggantikan jenis_catatan
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status_pembinaan' => [ // Contoh: "Selesai", "Proses", "Panggilan Ortu"
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'tindak_lanjut' => [ // Narasi tindakan yang diambil guru/sekolah
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatan' => [ // Menggantikan deskripsi
                'type' => 'TEXT',
            ],
            'tanggal' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['siswa_id', 'rombel_id']);
        
        $this->forge->createTable('catatan_akhlak');
    }

    public function down()
    {
        $this->forge->dropTable('catatan_akhlak');
    }
}