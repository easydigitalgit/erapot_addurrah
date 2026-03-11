<?php

namespace App\Models\Admin; // Namespace harus sesuai folder

use CodeIgniter\Model;

class InformasiPribadiModel extends Model
{
    protected $table            = 'informasi_pribadi';
    protected $primaryKey       = 'id';
    
    // Pastikan semua kolom yang mau diisi ada di sini
    protected $allowedFields    = [
        'user_id', 
        'nama_lengkap', 
        'no_hp',    
        'no_darurat', 
        'alamat_domisili', // Cek ejaan!
        'foto'
    ];
    
    protected $useTimestamps    = true;
}