<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MataPelajaranModel extends Model
{
    protected $table            = 'mata_pelajaran'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Pastikan kkm masuk ke sini agar diizinkan sistem
    protected $allowedFields    = [
        'kode_mapel', 
        'nama_mapel', 
        'kkm', 
        'kelompok', 
        'kurikulum_id', 
        'jp_minggu', 
        'status'
    ];

    protected $useTimestamps = false; 
}