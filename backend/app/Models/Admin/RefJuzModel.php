<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RefJuzModel extends Model
{
    protected $table            = 'ref_juz';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_juz', 'keterangan'];
}