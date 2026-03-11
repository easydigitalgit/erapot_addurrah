<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class NilaiAkademikModel extends Model
{
    protected $table            = 'nilai_akademik';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'siswa_id', 'guru_id', 'mapel_id', 'rombel_id', 
        'nilai_angka', 'predikat', 'catatan', 
        'tahun_ajaran', 'semester'
    ];
    protected $useTimestamps    = true;
}