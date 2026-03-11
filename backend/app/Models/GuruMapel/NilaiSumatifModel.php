<?php

namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class NilaiSumatifModel extends Model
{
    protected $table            = 'nilai_sumatif';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['siswa_id', 'mapel_id', 'jenis_sumatif', 'nilai', 'deskripsi', 'status'];
    protected $useTimestamps    = true;
}