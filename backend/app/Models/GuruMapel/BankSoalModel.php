<?php
namespace App\Models\GuruMapel;
use CodeIgniter\Model;

class BankSoalModel extends Model
{
    protected $table            = 'bank_soal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'guru_id', 'mapel_id', 'tingkat', 'jenis', 'pertanyaan', 
        'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 
        'kunci_jawaban', 'pembahasan', 'tingkat_kesulitan', 'kd', 'status'
    ];

    protected $useTimestamps = false; 
}