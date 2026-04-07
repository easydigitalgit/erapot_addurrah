<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GuruMapelModel extends Model
{
    protected $table            = 'guru_mapel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    // FIX MUTLAK 1: Ubah tahun_ajaran menjadi tahun_ajaran_id
    protected $allowedFields    = [
        'guru_id', 'mapel_id', 'rombel_id',
        'tahun_ajaran_id', 'jam_per_minggu', 
        'catatan', 'status'
    ];
    protected $useTimestamps    = true;

    public function getAllMappings()
    {
        // FIX MUTLAK 2: Join tabel tahun_ajaran supaya teks "2025/2026 (Genap)" bisa dimunculkan di UI
        return $this->select('guru_mapel.*, guru_tendik.nama_lengkap as teacher, guru_tendik.nik, mata_pelajaran.nama_mapel as mapel, rombel.nama_rombel, rombel.tingkat, tahun_ajaran.tahun as ta_tahun, tahun_ajaran.semester as ta_semester', false)
            ->join('guru_tendik', 'guru_tendik.id = guru_mapel.guru_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_mapel.mapel_id', 'left')
            ->join('rombel', 'rombel.id = guru_mapel.rombel_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = guru_mapel.tahun_ajaran_id', 'left')
            ->orderBy('guru_tendik.nama_lengkap', 'ASC')
            ->findAll();
    }

    public function checkDuplicate($guruId, $mapelId, $rombelId)
    {
        return $this->where([
            'guru_id'   => $guruId,
            'mapel_id'  => $mapelId,
            'rombel_id' => $rombelId
        ])->first();
    }
}