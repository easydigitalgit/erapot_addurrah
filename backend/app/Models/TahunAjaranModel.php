<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    
    // Pastikan allowedFields memuat tgl_mulai dan tgl_akhir
    protected $allowedFields    = [
        'tahun', 
        'semester', 
        'status', 
        'is_locked', 
        'tgl_mulai', 
        'tgl_akhir'
    ];

    // Opsional: Untuk memastikan return return datanya rapi
    protected $useTimestamps    = false; 
    protected $returnType       = 'array';
}