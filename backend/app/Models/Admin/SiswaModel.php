<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'rombel_id',
        'nis',
        'nisn',
        'nik',
        'nama_lengkap',
        'email_siswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_dalam_keluarga',
        'anak_ke',
        'alamat_siswa',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'alat_transportasi',
        'no_hp',
        'no_telp_rumah',
        'skhun',
        'penerima_kps',
        'no_kps',
        'no_peserta_un',
        'no_seri_ijazah',
        'penerima_kip',
        'nomor_kip',
        'nama_di_kip',
        'nomor_kks',
        'no_registrasi_akta',
        'layak_pip',
        'alasan_layak_pip',
        'kebutuhan_khusus',
        'no_kk',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'jml_saudara_kandung',
        'jarak_ke_sekolah',
        'asal_sekolah',
        'diterima_dikelas',
        'tgl_diterima',
        'ekskul_1',
        'ekskul_2',
        'ekskul_3', // <--- FITUR BARU EKSKUL
        'foto_siswa',
        'status_siswa'
    ];

    public function getSiswaLengkap()
    {
        // OPTIMASI & PRESISI: Menggunakan nama kolom asli dari database ustadz (id_tahun_ajaran)
        return $this->select('siswa.id, siswa.nis, siswa.nisn, siswa.nama_lengkap, siswa.jenis_kelamin, 
                              siswa.tempat_lahir, siswa.tanggal_lahir, siswa.no_hp, siswa.foto_siswa,
                              siswa.rombel_id, siswa.status_siswa,
                              rombel.nama_rombel, rombel.tingkat, rombel.id_tahun_ajaran,
                              ortu.nama_ayah, ortu.nama_ibu')
            ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
            ->join('orangtua_wali as ortu', 'ortu.siswa_id = siswa.id', 'left')
            ->orderBy('siswa.id', 'DESC')
            ->findAll();
    }

    public function countSiswaByGuru($guruId)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('jadwal_pelajaran');
        $builder->select('rombel_id');
        $builder->where('guru_id', $guruId);
        $builder->groupBy('rombel_id');
        $queryRombel = $builder->get()->getResultArray();

        if (empty($queryRombel)) {
            return 0;
        }

        $rombelIds = array_column($queryRombel, 'rombel_id');

        return $this->whereIn('rombel_id', $rombelIds)
            ->where('status_siswa', 'Aktif')
            ->countAllResults();
    }
}