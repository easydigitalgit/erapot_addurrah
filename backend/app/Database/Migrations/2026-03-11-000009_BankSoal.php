<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class BankSoal extends Migration
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
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tingkat' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'comment'    => 'Contoh: VII, VIII, IX',
            ],
            'jenis' => [ // Menggantikan jenis_soal
                'type'       => 'ENUM',
                'constraint' => ['pg', 'isian', 'esai'],
            ],
            'pertanyaan' => [
                'type' => 'TEXT',
            ],
            'opsi_a' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'opsi_b' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'opsi_c' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'opsi_d' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'kunci_jawaban' => [ // Menggantikan jawaban_benar
                'type' => 'TEXT',
            ],
            'pembahasan' => [ // Kolom baru untuk feedback siswa
                'type' => 'TEXT',
                'null' => true,
            ],
            'tingkat_kesulitan' => [ // Kolom baru untuk analisis soal
                'type'       => 'ENUM',
                'constraint' => ['mudah', 'sedang', 'sulit'],
            ],
            'kd' => [ // Kompetensi Dasar
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'aktif',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['guru_id', 'mapel_id']); // Index untuk mempercepat filter bank soal
        
        $this->forge->createTable('bank_soal');
    }

    public function down()
    {
        $this->forge->dropTable('bank_soal');
    }
}