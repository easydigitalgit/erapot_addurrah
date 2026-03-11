<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GuruMapelModel extends Model
{
    protected $table            = 'guru_mapel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'guru_id', 'mapel_id', 'rombel_id',
        'tahun_ajaran', 'jam_per_minggu', 
        'catatan', 'status'
    ];
    protected $useTimestamps    = true;

    // Ambil Data Lengkap (Join)
    // File: app/Models/Admin/GuruMapelModel.php

    public function getAllMappings()
    {
        return $this->select('guru_mapel.*')
            // UBAH BARIS INI: Ambil kolom 'nik'
            ->select('guru_tendik.nama_lengkap as teacher, guru_tendik.nik') 
            ->select('mata_pelajaran.nama_mapel as mapel')
            ->select('rombel.nama_rombel, rombel.tingkat')
            ->join('guru_tendik', 'guru_tendik.id = guru_mapel.guru_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_mapel.mapel_id', 'left')
            ->join('rombel', 'rombel.id = guru_mapel.rombel_id', 'left')
            ->orderBy('guru_tendik.nama_lengkap', 'ASC')
            ->findAll();
    }

    // Cek Duplikasi
    public function checkDuplicate($guruId, $mapelId, $rombelId)
    {
        return $this->where([
            'guru_id'   => $guruId,
            'mapel_id'  => $mapelId,
            'rombel_id' => $rombelId
        ])->first();
    }
}