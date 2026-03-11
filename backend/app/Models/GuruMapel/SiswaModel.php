<?php
namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    // Tetap mengarah ke tabel 'siswa' yang sama di database
    protected $table            = 'siswa'; 
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    
    // Guru hanya butuh data dasar, kita batasi aksesnya
    protected $allowedFields    = ['nis', 'nama_lengkap', 'rombel_id']; 

    /**
     * Method untuk mengambil siswa, dioptimalkan khusus untuk Guru
     */
    public function getSiswaByKelas($rombel_id)
    {
        // Kita gunakan select() agar tidak menarik data berat/sensitif milik Admin
        return $this->select('id, nis, nama_lengkap')
                    ->where('rombel_id', $rombel_id)
                    ->orderBy('nama_lengkap', 'ASC') 
                    ->findAll();
    }
}