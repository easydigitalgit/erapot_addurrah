<?php
namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class RubrikProyekModel extends Model
{
    protected $table            = 'rubrik_proyek';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'proyek_id', 
        'nama_aspek', 
        'bobot'
    ];

    protected $useTimestamps = false; 
}