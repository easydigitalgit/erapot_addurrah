<?php
namespace App\Models\GuruMapel;
use CodeIgniter\Model;

class PaketSoalModel extends Model
{
    protected $table            = 'paket_soal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'guru_id', 'mapel_id', 'tingkat', 'nama_paket', 
        'tanggal', 'kelas_target', 'kumpulan_soal_id', 'status'
    ];

    protected $useTimestamps = false; 
}