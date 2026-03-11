<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table            = 'notifikasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['user_id', 'judul', 'pesan', 'tipe', 'link', 'is_read', 'created_at'];

    // Fungsi untuk menghitung jumlah notifikasi yang BELUM dibaca
    public function countUnread($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    // Fungsi untuk mengambil notifikasi terbaru
    public function getLatest($userId, $limit = 5)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
}