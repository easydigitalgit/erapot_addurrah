<?php

namespace App\Models;

use CodeIgniter\Model;

class TahfidzModel extends Model
{
    protected $table            = 'setoran_tahfidz';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $allowedFields    = [
        'siswa_id', 
        'guru_id', 
        'tanggal', 
        'jenis_setoran', 
        'surah', 
        'ayat', 
        'predikat', 
        'catatan'
    ];

    // Aktifkan timestamp agar created_at dan updated_at terisi otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}