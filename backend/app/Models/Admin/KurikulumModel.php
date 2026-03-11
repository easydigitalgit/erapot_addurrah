<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class KurikulumModel extends Model
{
    protected $table            = 'kurikulum';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_kurikulum', 'jenis', 'tahun_berlaku', 'status'];

    // Fitur untuk mencatat waktu create/update (opsional, jika tabel ada kolom created_at)
    protected $useTimestamps = false; 
}