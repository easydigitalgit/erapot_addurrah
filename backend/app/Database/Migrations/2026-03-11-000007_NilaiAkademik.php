<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NilaiAkademik extends Migration
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
                'unsigned'   => true, // Wajib unsigned sesuai target
            ],
            'guru_id' => [ // Kolom baru sesuai target
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'rombel_id' => [ // Kolom baru sesuai target
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'jenis_penilaian' => [ // Contoh: Tugas, UH, UTS, UAS
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'pertemuan' => [ // Untuk mencatat nilai pertemuan ke-berapa
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tanggal_penilaian' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status_simpan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'draft',
                'null'       => true,
            ],
            'nilai_angka' => [ // Satu kolom untuk semua jenis nilai
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => true,
            ],
            'predikat' => [
                'type'       => 'CHAR',
                'constraint' => 2,
                'null'       => true,
            ],
            'catatan' => [ // Menggantikan keterangan
                'type' => 'TEXT',
                'null' => true,
            ],
            'tahun_ajaran' => [ // Berubah dari ID ke VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['Ganjil', 'Genap'],
                'default'    => 'Ganjil',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // Index untuk mempercepat query nilai per siswa di tahun/semester tertentu
        $this->forge->addKey(['siswa_id', 'mapel_id', 'tahun_ajaran', 'semester']);
        $this->forge->createTable('nilai_akademik');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_akademik');
    }
}