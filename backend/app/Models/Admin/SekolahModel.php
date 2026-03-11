<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
// ... kode sebelumnya ...
    protected $allowedFields = [
        'nama_sekolah', 'npsn', 'nss', 'jenjang', 
        'status_sekolah', 'tahun_berdiri', 'akreditasi',
        'alamat', 'kecamatan', 'kabupaten', 'provinsi', 
        'kode_pos', 'telepon', 'email', 'website', 'logo',
        'warna_primary', 'warna_secondary',
        'desa_id' // <--- WAJIB DITAMBAHKAN
    ];
// ... kode setelahnya ...

    // Mengaktifkan timestamp otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}