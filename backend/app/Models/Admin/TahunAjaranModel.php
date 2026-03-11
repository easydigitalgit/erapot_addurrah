<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    // KUNCI PERBAIKAN: Semua kolom SQL harus didaftarkan di sini agar diizinkan masuk ke database
    protected $allowedFields    = [
        'tahun', 
        'semester', 
        'status', 
        'tgl_mulai', 
        'tgl_akhir', 
        'is_locked'
    ];
}