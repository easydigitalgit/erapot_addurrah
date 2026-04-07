<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class SetoranTahfidz extends Migration
{
    public function up() 
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT', 
                'constraint'     => 11, 
                'unsigned'       => true, 
                'auto_increment' => true
            ],
            'siswa_id' => [
                'type'       => 'INT', 
                'constraint' => 11
            ],
            'guru_id' => [
                'type'       => 'INT', 
                'constraint' => 11,
                'comment'    => 'ID Guru penyimak'
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'jenis_setoran' => [
                'type'       => 'ENUM', 
                'constraint' => ['Ziyadah', 'Murojaah'],
                'default'    => 'Ziyadah'
            ],
            'surah' => [ // Menggabungkan surah_mulai dan sampai menjadi satu kolom teks
                'type'       => 'VARCHAR', 
                'constraint' => 100,
                'comment'    => 'Nama Surah'
            ],
            'ayat' => [ // Menggabungkan ayat_mulai dan sampai menjadi satu kolom teks
                'type'       => 'VARCHAR', 
                'constraint' => 50,
                'comment'    => 'Rentang Ayat'
            ],
            'predikat' => [
                'type'       => 'ENUM', 
                'constraint' => ['Sangat Lancar', 'Lancar', 'Kurang Lancar', 'Tidak Lancar'],
                'default'    => 'Lancar'
            ],
            'catatan' => [
                'type' => 'TEXT', 
                'null' => true
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
        $this->forge->addKey(['siswa_id', 'tanggal']); 
        
        $this->forge->createTable('setoran_tahfidz');
    }

    public function down() 
    { 
        $this->forge->dropTable('setoran_tahfidz'); 
    }
}