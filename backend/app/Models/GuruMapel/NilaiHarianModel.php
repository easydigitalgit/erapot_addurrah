<?php
namespace App\Models\GuruMapel;

use CodeIgniter\Model;

class NilaiHarianModel extends Model
{
    // Arahkan ke tabel nilai_akademik
    protected $table            = 'nilai_akademik'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $useTimestamps    = true; 
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // TUGAS BARU: Tambahkan 3 kolom yang baru saja kamu buat di phpMyAdmin ke sini
    protected $allowedFields    = [
        'siswa_id', 'guru_id', 'mapel_id', 'rombel_id', 
        'nilai_angka', 'predikat', 'catatan', 'tahun_ajaran', 
        'semester', 'jenis_penilaian', 'pertemuan', 'tanggal_penilaian',
        'status_simpan'
    ];
}