<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * @param array|null $arguments Array role yang diizinkan dari Routes (contoh: ['admin', 'guru'])
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. CEK LOGIN: Apakah user sudah login?
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, tendang ke halaman login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. CEK ARGUMEN: Apakah route ini butuh role khusus?
        // Jika di Routes cuma ditulis ['filter' => 'role'] tanpa parameter tambahan, berarti cuma butuh login saja.
        if (empty($arguments)) {
            return;
        }

        // 3. AMBIL ROLE USER SAAT INI
        // Kita ambil dari session yang diset saat LoginController ('role_key')
        $userRole = session()->get('role_key');

        // 4. CEK KECOCOKAN ROLE
        // $arguments berisi daftar role yang boleh masuk. Contoh: ['admin', 'kepsek']
        if (!in_array($userRole, $arguments)) {
            // SKENARIO DITOLAK: Redirect ke login dengan pesan tegas
            return redirect()->to('/login')->with('error', 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada yang perlu dilakukan setelah request
    }
}