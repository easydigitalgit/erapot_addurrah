<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MasterEkskulModel extends Model
{
    protected $table            = 'master_ekskul';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_ekskul', 'status'];
    protected $useTimestamps    = true;
}