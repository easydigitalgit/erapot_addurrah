<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table            = 'orangtua_wali';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
        
    // PERBAIKAN: Semua field dapodik didaftarkan di sini agar bisa masuk ke Database!
    protected $allowedFields = [
        'user_id', 
        'siswa_id', 
        
        // Data Ayah
        'nama_ayah', 'nik_ayah', 'tahun_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
        
        // Data Ibu
        'nama_ibu', 'nik_ibu', 'tahun_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
        
        // Data Wali
        'nama_wali', 'nik_wali', 'pekerjaan_wali',
        
        // Kontak
        'email_ortu', 'no_hp_ortu', 'alamat_orangtua'
    ];

    protected $useTimestamps = false; 

    // ... (Biarkan fungsi getParentsComplete() di bawahnya tetap seperti yang sudah ada) ...

    // ... (kode properti yang sudah ada) ...

    /**
     * Ambil data orang tua lengkap dengan Nama Siswa & Nama Kelas
     */
/**
     * Ambil data orang tua lengkap dengan Filter
     */
    public function getParentsComplete($keyword = null, $relation = null, $kelas = null, $status = null)
    {
        $builder = $this->select('orangtua_wali.*, siswa.nama_lengkap as nama_siswa, rombel.nama_rombel, rombel.tingkat, users.is_active, users.foto_profil')
                        ->join('siswa', 'siswa.id = orangtua_wali.siswa_id', 'left')
                        ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                        ->join('users', 'users.id = orangtua_wali.user_id', 'left');

        // 1. Filter Keyword (Pencarian Global)
        if ($keyword) {
            $builder->groupStart()
                ->like('orangtua_wali.nama_ayah', $keyword)
                ->orLike('orangtua_wali.nama_ibu', $keyword)
                ->orLike('orangtua_wali.nama_wali', $keyword)
                ->orLike('siswa.nama_lengkap', $keyword)
                ->orLike('orangtua_wali.no_hp_ortu', $keyword)
                ->orLike('orangtua_wali.email_ortu', $keyword)
            ->groupEnd();
        }

        // 2. Filter Hubungan
        if ($relation) {
            if ($relation == 'Ayah') {
                $builder->groupStart()->where('nama_ayah !=', '-')->where('nama_ayah !=', '')->groupEnd();
            } elseif ($relation == 'Ibu') {
                $builder->groupStart()->where('nama_ibu !=', '-')->where('nama_ibu !=', '')->groupEnd();
            } elseif ($relation == 'Wali') {
                $builder->groupStart()->where('nama_wali !=', '-')->where('nama_wali !=', '')->groupEnd();
            }
        }

        // 3. Filter Kelas (Berdasarkan Tingkat)
        if ($kelas) {
            $builder->where('rombel.tingkat', $kelas);
        }

        // 4. Filter Status Akun
        if ($status) {
            $isActive = ($status == 'Aktif') ? 1 : 0;
            if ($status == 'Belum Aktivasi') {
                // User belum dibuat atau ID user 0/null
                $builder->groupStart()->where('users.is_active', 0)->orWhere('user_id', 0)->orWhere('user_id IS NULL')->groupEnd();
            } else {
                $builder->where('users.is_active', $isActive);
            }
        }

        return $builder->orderBy('orangtua_wali.id', 'DESC')->findAll();
    }

    
}