<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'jadwal_pelajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'rombel_id', 
        'mapel_id', 
        'guru_id', 
        'hari', 
        'jam_mulai', 
        'jam_selesai',
        'kode_jadwal_excel', // Tambahkan ini
        'jenis_jadwal'       // Tambahkan ini
    ];

    protected $useTimestamps = false; 

    public function getKelasAjarGuru($guruId)
    {
        // Fungsi ini aman, hanya join ke rombel
        return $this->select('jadwal_pelajaran.rombel_id as id, rombel.nama_rombel as nama_kelas, rombel.tingkat')
                    ->select('(SELECT COUNT(*) FROM siswa WHERE siswa.rombel_id = jadwal_pelajaran.rombel_id AND siswa.status_siswa = "Aktif") as jumlah_siswa')
                    ->join('rombel', 'rombel.id = jadwal_pelajaran.rombel_id')
                    ->where('jadwal_pelajaran.guru_id', $guruId)
                    ->groupBy('jadwal_pelajaran.rombel_id')
                    ->findAll();
    }

    /**
     * Mengambil jadwal hari ini untuk Guru tersebut
     */
    public function getJadwalHariIni($guruId)
    {
        $hariInggris = date('l'); 
        $hariIndo = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariIndo[$hariInggris];

        // --- PERBAIKAN UTAMA ADA DI SINI ---
        // 1. Select: mata_pelajaran.nama_mapel (bukan mapel.nama_mapel)
        // 2. Join: ke tabel 'mata_pelajaran' (bukan 'mapel')
        
        return $this->select('jadwal_pelajaran.*, rombel.nama_rombel as nama_kelas, mata_pelajaran.nama_mapel')
                    ->join('rombel', 'rombel.id = jadwal_pelajaran.rombel_id')
                    ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id') // <--- INI PERBAIKANNYA
                    ->where('jadwal_pelajaran.guru_id', $guruId)
                    ->where('jadwal_pelajaran.hari', $hariIni)
                    ->orderBy('jadwal_pelajaran.jam_mulai', 'ASC')
                    ->findAll();
    }
}