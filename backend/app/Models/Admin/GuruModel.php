<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'jadwal_pelajaran'; // Sesuaikan nama tabel Anda
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
        'ruangan' // Tambahkan ini jika di tabel ada kolom ruangan
    ];

    // Opsional: Gunakan timestamps jika tabel punya created_at/updated_at
    protected $useTimestamps = false; 
}