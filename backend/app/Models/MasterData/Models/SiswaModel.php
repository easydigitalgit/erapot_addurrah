<?php
namespace App\Models\MasterData\Models;

use CodeIgniter\Model;

class SiswaModel extends Model{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields =[
        'user_id','rombel_id','nis','nisn','nama_lengkap','email_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','agama','status_dalam_keluarga','anak_ke','alamat_siswa','no_telp_rumah','asal_sekolah','diterima_dikelas','tgl_diterima','foto_siswa','status_siswa'
    ];

    protected $useTimestamps = false;
}

?>