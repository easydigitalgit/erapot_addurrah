<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'desa'; // Sesuai nama tabel di database
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    // Kita ambil kode dan nama untuk ditampilkan di dropdown
    protected $allowedFields = ['kode', 'kec_id', 'nama'];
}