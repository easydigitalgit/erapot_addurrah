<?php

namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class PenilaianProyekModel extends Model
{
    protected $table            = 'penilaian_proyek';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'guru_id', 
        'mapel_id', 
        'rombel_id', 
        'nama_proyek', 
        'jenis', 
        'tanggal_pelaksanaan', 
        'kkm', 
        'deskripsi', 
        'status'
    ];

    protected $useTimestamps = false; 
}