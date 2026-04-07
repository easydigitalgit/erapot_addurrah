<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['kode_mapel' => 'PAI',     'nama_mapel' => 'Pendidikan Agama Islam dan Budi Pekerti',   'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'PKN',     'nama_mapel' => 'Pendidikan Pancasila',                      'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'BIND',    'nama_mapel' => 'Bahasa Indonesia',                          'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'MTK',     'nama_mapel' => 'Matematika',                                'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'IPA',     'nama_mapel' => 'Ilmu Pengetahuan Alam',                     'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'IPS',     'nama_mapel' => 'Ilmu Pengetahuan Sosial',                   'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'BING',    'nama_mapel' => 'Bahasa Inggris',                            'kelompok' => 'A', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'SBD',     'nama_mapel' => 'Seni dan Budaya',                           'kelompok' => 'B', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'PJOK',    'nama_mapel' => 'Pendidikan Jasmani Olahraga dan Kesehatan', 'kelompok' => 'B', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'PRK',     'nama_mapel' => 'Prakarya / Informatika',                    'kelompok' => 'B', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'MULOK',   'nama_mapel' => 'Muatan Lokal Bahasa Daerah',                'kelompok' => 'Muatan Lokal', 'kkm' => 75, 'status' => 'Aktif'],
            ['kode_mapel' => 'TAHFIDZ', 'nama_mapel' => 'Tahfidz',                                   'kelompok' => 'Muatan Lokal', 'kkm' => 75, 'status' => 'Aktif'],
        ];

        $this->db->table('mata_pelajaran')->truncate();
        $this->db->table('mata_pelajaran')->insertBatch($data);
        
        echo "Data Mata Pelajaran berhasil disemai!\n";
    }
}