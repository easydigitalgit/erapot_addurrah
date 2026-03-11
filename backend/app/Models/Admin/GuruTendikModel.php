<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GuruTendikModel extends Model
{
    protected $table            = 'guru_tendik';
    protected $primaryKey       = 'id';
    protected $allowedFields = [
    'user_id', 
    'nama_lengkap', 
    'nuptk', 
    'nik', 
    'email', 
    'no_hp', 
    'tempat_lahir', 
    'tanggal_lahir', 
    'jabatan', 
    'status_kepegawaian', 
    'mapel_utama', 
    'foto', 
    'is_active'
    // Pastikan tidak ada yang kurang!
];
}