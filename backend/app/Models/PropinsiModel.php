<?php

namespace App\Models;

use CodeIgniter\Model;

class PropinsiModel extends Model
{
    protected $table = 'propinsi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['kode', 'nama'];
}