<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'user_id', 'rombel_id', 'nis', 'nisn', 'nik', 
        'nama_lengkap', 'email_siswa', 'jenis_kelamin', 
        'tempat_lahir', 'tanggal_lahir', 'agama', 
        'status_dalam_keluarga', 'anak_ke', 
        'alamat_siswa', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos',
        'jenis_tinggal', 'alat_transportasi', 'no_hp', 'no_telp_rumah', 
        'skhun', 'penerima_kps', 'no_kps', 'no_peserta_un', 'no_seri_ijazah',
        'penerima_kip', 'nomor_kip', 'nama_di_kip', 'nomor_kks', 'no_registrasi_akta',
        'layak_pip', 'alasan_layak_pip', 'kebutuhan_khusus', 'no_kk', 
        'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'jml_saudara_kandung', 'jarak_ke_sekolah',
        'asal_sekolah', 'diterima_dikelas', 'tgl_diterima', 
        'ekskul_1', 'ekskul_2', 'ekskul_3', // <--- FITUR BARU EKSKUL
        'foto_siswa', 'status_siswa'
    ];

   public function getSiswaLengkap()
    {
        return $this->select('siswa.*, 
                              siswa.jenis_kelamin as jk_siswa, 
                              siswa.status_siswa as stat_siswa, 
                              siswa.status_dalam_keluarga as stat_keluarga, 
                              siswa.ekskul_1, siswa.ekskul_2, siswa.ekskul_3,
                              rombel.nama_rombel, 
                              rombel.tingkat, 
                              rombel.kurikulum, 
                              users.foto_profil, 
                              users.username, 
                              COALESCE(guru_tendik.nama_lengkap, "") as nama_wali_kelas,
                              ortu.nama_ayah, ortu.nik_ayah, ortu.tahun_lahir_ayah, ortu.pendidikan_ayah, ortu.pekerjaan_ayah, ortu.penghasilan_ayah,
                              ortu.nama_ibu, ortu.nik_ibu, ortu.tahun_lahir_ibu, ortu.pendidikan_ibu, ortu.pekerjaan_ibu, ortu.penghasilan_ibu,
                              ortu.nama_wali, ortu.nik_wali, ortu.pekerjaan_wali, ortu.no_hp_ortu,
                              ortu.tahun_lahir_wali, ortu.pendidikan_wali, ortu.penghasilan_wali, ortu.email_ortu, ortu.alamat_orangtua')
            ->join('rombel', 'rombel.id = siswa.rombel_id', 'left') 
            ->join('users', 'users.id = siswa.user_id', 'left') 
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left') 
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