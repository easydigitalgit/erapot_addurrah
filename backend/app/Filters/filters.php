<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // --- MODE DETEKTIF: ON ---
        // Kode ini akan menampilkan isi Session Anda di layar putih
        dd(session()->get()); 
        // -------------------------
    
        // 1. Cek Login
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // 2. Cek Role (Jika filter dipanggil dengan parameter, misal: ['filter' => 'role:1,2'])
        if (!empty($arguments)) {
            $userRole = session()->get('role_id');
            
            // Jika role user saat ini TIDAK ada dalam daftar yang diizinkan
            if (!in_array($userRole, $arguments)) {
                // Tendang ke halaman dashboard atau tampilkan error 403
                // Sesuaikan '/dashboard' dengan halaman utama aplikasi Anda
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}