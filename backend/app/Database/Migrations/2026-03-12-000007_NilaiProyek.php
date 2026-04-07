<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class NilaiProyek extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'proyek_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'siswa_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
            ],
            'nilai_json' => [
                'type'           => 'TEXT',
                'null'           => false,
                'comment'        => 'Menyimpan nilai per aspek rubrik (JSON)',
            ],
            'nilai_akhir' => [
                'type'           => 'INT',
                'null'           => false,
                'default'        => 0,
            ],
            'catatan' => [
                'type'           => 'TEXT',
                'null'           => true,
                'default'        => null,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                // Gunakan RawSql untuk nilai default CURRENT_TIMESTAMP bawaan MySQL (khusus CI4 versi 4.2.2 ke atas)
                'default'        => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        // Membuat Primary Key
        $this->forge->addKey('id', true);

        // Membuat Unique Index (proyek_siswa_unik)
        $this->forge->addUniqueKey(['proyek_id', 'siswa_id'], 'proyek_siswa_unik');

        // Membuat Index biasa (fk_nilai_siswa)
        $this->forge->addKey('siswa_id', false, false, 'fk_nilai_siswa');

        // (Opsional) Jika Anda ingin menambahkan Foreign Key Constraints secara eksplisit di level database:
        // $this->forge->addForeignKey('proyek_id', 'penilaian_proyek', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'CASCADE');

        // Mengeksekusi pembuatan tabel
        $this->forge->createTable('nilai_proyek');
    }

    public function down()
    {
        // Menghapus tabel jika migrasi di-rollback
        $this->forge->dropTable('nilai_proyek');
    }
}