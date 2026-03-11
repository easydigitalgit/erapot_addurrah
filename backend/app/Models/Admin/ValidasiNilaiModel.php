<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ValidasiNilaiModel extends Model
{
    // Tabel khusus untuk menyimpan status validasi dan lock nilai
    protected $table            = 'validasi_nilai';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'rombel_id', 
        'progress_akademik', 
        'progress_karakter', // Pastikan kolom ini ada di database
        'progress_tahfidz',  // Pastikan kolom ini ada di database
        'is_locked', 
        'locked_at',
        'locked_by'          // TAMBAHAN: Untuk menyimpan ID user yang melakukan lock
    ];
    
    // Aktifkan timestamps agar created_at & updated_at terisi otomatis
    protected $useTimestamps = true;

    /**
     * Mengambil daftar rombel beserta nama wali kelas dan status validasinya
     * Digunakan di Halaman Validasi Nilai (Admin)
     */
    public function getRekapValidasi()
    {
        return $this->db->table('rombel')
            ->select('
                rombel.id as rombel_id, 
                rombel.tingkat, 
                rombel.nama_rombel, 
                guru_tendik.nama_lengkap as wali_kelas,  
                guru_tendik.nuptk, 
                validasi_nilai.progress_akademik, 
                validasi_nilai.is_locked,
                validasi_nilai.locked_at
            ')
            // Join ke data Guru (Wali Kelas)
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            
            // Join ke tabel validasi_nilai
            ->join('validasi_nilai', 'validasi_nilai.rombel_id = rombel.id', 'left')
            
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Fungsi untuk mengunci (lock) nilai berdasarkan rombel_id
     */
    public function lockNilai($rombel_id, $user_id = 1) // Default user_id 1 (Admin) jika tidak ada session
    {
        // Cek apakah data validasi untuk rombel ini sudah ada
        $cek = $this->where('rombel_id', $rombel_id)->first();

        $data = [
            'is_locked' => 1,
            'locked_at' => date('Y-m-d H:i:s'),
            'locked_by' => $user_id, // Simpan siapa yang nge-lock
            'progress_akademik' => 100 // Set 100% jika di-lock manual
        ];

        if ($cek) {
            // Jika ada, update
            return $this->update($cek['id'], $data);
        } else {
            // Jika belum ada, insert baru
            $data['rombel_id'] = $rombel_id;
            return $this->insert($data);
        }
    }
    
    /**
     * Fungsi untuk membuka kunci (Unlock) - Opsional jika ada fitur revisi
     */
    public function unlockNilai($rombel_id)
    {
        $cek = $this->where('rombel_id', $rombel_id)->first();
        if ($cek) {
            return $this->update($cek['id'], [
                'is_locked' => 0,
                'locked_at' => null,
                'locked_by' => null
            ]);
        }
        return false;
    }
}