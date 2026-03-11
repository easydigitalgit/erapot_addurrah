<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class TargetTahfidzModel extends Model
{
    protected $table            = 'target_tahfidz';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'tingkat', 'semester', 'juz_id', 
        'surah_mulai_id', 'surah_sampai_id', 
        'minimal_hafalan', 'status'
    ];
    protected $useTimestamps    = true;

    // Fungsi untuk mengambil data lengkap dengan Join
    public function getTargetLengkap($id = null)
    {
        $builder = $this->select('target_tahfidz.*, 
                                  ref_juz.nama_juz, 
                                  s_mulai.nama_surah as surah_mulai, 
                                  s_sampai.nama_surah as surah_sampai')
                        ->join('ref_juz', 'ref_juz.id = target_tahfidz.juz_id')
                        ->join('ref_surah as s_mulai', 's_mulai.id = target_tahfidz.surah_mulai_id', 'left')
                        ->join('ref_surah as s_sampai', 's_sampai.id = target_tahfidz.surah_sampai_id', 'left');

        if ($id) {
            return $builder->where('target_tahfidz.id', $id)->first();
        }
        
        return $builder->orderBy('target_tahfidz.tingkat', 'ASC')
                       ->orderBy('target_tahfidz.semester', 'ASC')
                       ->findAll();
    }
}