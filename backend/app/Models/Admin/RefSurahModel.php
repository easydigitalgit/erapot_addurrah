<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RefSurahModel extends Model
{
    protected $table            = 'ref_surah';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['no_surah', 'nama_surah', 'nama_surah_arab', 'arti_surah', 'jumlah_ayat'];
}