<?php

namespace App\Models;

use CodeIgniter\Model;

class KabupatenModel extends Model
{
    protected $table = 'kabupaten';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['kode', 'propinsi', 'nama'];
}