<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NilaiKomponen extends Migration
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
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap'],
            ],
            // Komponen Nilai Akademik
            'harian' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'uts' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'uas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'proyek' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Aspek Penilaian Karakter (Sikap)
            'aspek_kedisiplinan' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
            ],
            'aspek_tanggung_jawab' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
            ],
            'aspek_kerjasama' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
            ],
            'aspek_kejujuran' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Index tambahan untuk mempercepat query rapor per siswa
        $this->forge->addKey(['siswa_id', 'mapel_id', 'tahun_ajaran']);
        
        $this->forge->createTable('nilai_komponen');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_komponen');
    }
}