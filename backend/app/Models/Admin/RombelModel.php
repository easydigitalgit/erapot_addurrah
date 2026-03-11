<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RombelModel extends Model
{
    protected $table            = 'rombel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Sesuai dengan gambar tabel database Anda
    protected $allowedFields    = [
        'id', 
        'nama_rombel', 
        'tingkat', 
        'kurikulum', 
        'wali_kelas_id', 
        'tahun_ajaran', 
        'semester',
    ];

    // Jika Anda menggunakan timestamps (created_at/updated_at), set true. 
    // Jika tidak ada di tabel (seperti di gambar), set false.
    protected $useTimestamps = false; 
}