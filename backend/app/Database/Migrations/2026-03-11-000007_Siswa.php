<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Siswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'rombel_id'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'nis'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nisn'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nik'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nama_lengkap'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'email_siswa'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'jenis_kelamin' => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'null' => true],
            'tempat_lahir'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tanggal_lahir' => ['type' => 'DATE', 'null' => true],
            'agama'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            
            // Data Keluarga & Alamat
            'status_dalam_keluarga' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'anak_ke'               => ['type' => 'INT', 'null' => true],
            'alamat_siswa'          => ['type' => 'TEXT', 'null' => true],
            'rt'                    => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'rw'                    => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'dusun'                 => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'kelurahan'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'kecamatan'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'kode_pos'              => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'jenis_tinggal'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'alat_transportasi'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            // Kontak & Asal Sekolah
            'no_telp_rumah'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'no_hp'                 => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'asal_sekolah'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'skhun'                 => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            // Data Bantuan Pemerintah
            'penerima_kps'          => ['type' => 'ENUM', 'constraint' => ['Ya', 'Tidak'], 'default' => 'Tidak'],
            'no_kps'                => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'no_peserta_un'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'no_seri_ijazah'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'penerima_kip'          => ['type' => 'ENUM', 'constraint' => ['Ya', 'Tidak'], 'default' => 'Tidak'],
            'nomor_kip'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nama_di_kip'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nomor_kks'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'no_registrasi_akta'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'layak_pip'             => ['type' => 'ENUM', 'constraint' => ['Ya', 'Tidak'], 'default' => 'Tidak'],
            'alasan_layak_pip'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            
            // Lain-lain
            'kebutuhan_khusus'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Tidak ada'],
            'no_kk'                 => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'diterima_dikelas'      => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'tgl_diterima'          => ['type' => 'DATE', 'null' => true],
            'foto_siswa'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status_siswa'          => ['type' => 'ENUM', 'constraint' => ['Aktif', 'Lulus', 'Pindah', 'Keluar'], 'default' => 'Aktif'],
            
            // Fisik
            'berat_badan'           => ['type' => 'INT', 'null' => true],
            'tinggi_badan'          => ['type' => 'INT', 'null' => true],
            'lingkar_kepala'        => ['type' => 'INT', 'null' => true],
            'jml_saudara_kandung'   => ['type' => 'INT', 'null' => true],
            'jarak_ke_sekolah'      => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('rombel_id');
        $this->forge->addKey('nisn');
        
        $this->forge->createTable('siswa');
    }

    public function down()
    {
        $this->forge->dropTable('siswa');
    }
}