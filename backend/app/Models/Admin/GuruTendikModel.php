<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class GuruTendikModel extends Model
{
    protected $table            = 'guru_tendik';
    protected $primaryKey       = 'id';
    protected $allowedFields = [
        'user_id',
        'nuptk',
        'nik',
        'nama_lengkap',
        'gelar',
        'email',
        'nama_pasangan',
        'jumlah_anak',
        'jenis_kelamin',
        'status_marital',
        'pendidikan_terakhir',
        'jurusan_prodi',
        'tmt_ad_durrah',
        'tempat_lahir',
        'tanggal_lahir',
        'suku',
        'golongan_darah',
        'alamat_ktp',
        'status_kepegawaian',
        'jabatan_id',
        'mapel_id',
        'alamat_domisili',
        'no_hp',
        'no_darurat',
        'ttd_digital',
        'is_active'
    ];
}
