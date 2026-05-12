<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiHarianModel extends Model
{
    protected $table            = 'absensi_harian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Field yang diizinkan untuk diisi sesuai struktur SQL kamu
    protected $allowedFields    = ['siswa_id', 'rombel_id', 'tanggal', 'status', 'keterangan'];

    // Mengaktifkan fitur otomatis isi kolom created_at & updated_at
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}