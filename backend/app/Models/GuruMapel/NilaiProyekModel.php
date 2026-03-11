<?php
namespace App\Models\GuruMapel;
use CodeIgniter\Model;

class NilaiProyekModel extends Model
{
    protected $table            = 'nilai_proyek';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'proyek_id', 'siswa_id', 'nilai_json', 'nilai_akhir', 'catatan'
    ];

    protected $useTimestamps = false; 
}