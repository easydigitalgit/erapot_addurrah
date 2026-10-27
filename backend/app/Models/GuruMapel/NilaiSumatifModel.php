<?php

namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class NilaiSumatifModel extends Model
{
    protected $table            = 'nilai_sumatif';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
    
    // ===================================================================================
    // FIX MUTLAK: Menambahkan 'tahun_ajaran_id' dan 'guru_id' agar data tidak diblokir CI4
    // ===================================================================================
    protected $allowedFields    = [
        'siswa_id', 
        //'guru_id', 
        'mapel_id', 
        'tahun_ajaran_id', 
        'kategori',
        'jenis_sumatif',
        'deleted_at', 
        'nilai', 
        'deskripsi', 
        'status'
    ];
    
    protected $useTimestamps    = true;
}