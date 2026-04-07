<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MasterLmModel extends Model
{
    protected $table            = 'master_lm';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // PERBAIKAN: Memasukkan kembali deskripsi_a sampai deskripsi_d
    protected $allowedFields    = [
        'tahun_ajaran_id',
        'kurikulum_id',
        'mapel_id',
        'tingkat',
        'semester',
        'kategori',
        'kode_lm',
        'deskripsi_lm',
        'deskripsi_a',
        'deskripsi_b',
        'deskripsi_c',
        'deskripsi_d',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllLM()
    {
        return $this->select('master_lm.*, mata_pelajaran.nama_mapel, tahun_ajaran.tahun as tahun_ajaran, tahun_ajaran.semester as ta_semester')
            // INNER JOIN: Membungkam data yatim piatu (null) secara otomatis
            ->join('mata_pelajaran', 'mata_pelajaran.id = master_lm.mapel_id', 'inner')
            ->join('tahun_ajaran', 'tahun_ajaran.id = master_lm.tahun_ajaran_id', 'left')
            ->orderBy('tahun_ajaran.tahun', 'DESC')
            ->orderBy('master_lm.tingkat', 'ASC')
            ->orderBy('mata_pelajaran.nama_mapel', 'ASC')
            ->orderBy('master_lm.kode_lm', 'ASC')
            ->findAll();
    }
}
