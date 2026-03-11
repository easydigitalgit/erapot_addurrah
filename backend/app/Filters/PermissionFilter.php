<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Cek apakah user sudah login
        // UBAH JADI isLoggedIn
        if (!$session->get('isLoggedIn') || !$session->get('role_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // ... (biarkan sisa kode ke bawah sama persis seperti sebelumnya)

       $role_id = $session->get('role_id');

        // --- JALUR VIP KHUSUS ADMIN SEKOLAH (ROLE ID 1) ---
        // Jika yang login adalah Admin, langsung loloskan ke semua halaman!
        if ($role_id == 1) {
            return; 
        }
        // --------------------------------------------------

        // 2. Jika tidak ada argumen modul yang dicek, anggap lolos
        if (empty($arguments)) {
            return;
        }

        $module = $arguments[0] ?? null;
        $action = $arguments[1] ?? 'view'; // default yang dicek adalah 'view'

        // 3. Cek ke database tabel `role_permissions`
        $db = \Config\Database::connect();
        $permission = $db->table('role_permissions')
                         ->where('role_id', $role_id)
                         ->where('module_name', $module)
                         ->get()
                         ->getRowArray();

        // 4. Logika Penolakan (Jika data tidak ada, atau nilainya 0)
        $isAllowed = false;

        if ($permission) {
            // Cek kolom sesuai action yang diminta
            switch ($action) {
                case 'view':    $isAllowed = ($permission['can_view'] == 1); break;
                case 'create':  $isAllowed = ($permission['can_create'] == 1); break;
                case 'update':  $isAllowed = ($permission['can_update'] == 1); break;
                case 'delete':  $isAllowed = ($permission['can_delete'] == 1); break;
                case 'special': $isAllowed = ($permission['can_special'] == 1); break;
            }
        }

        // 5. Tendang jika tidak punya akses!
        if (!$isAllowed) {
            
            /** @var \CodeIgniter\HTTP\IncomingRequest $request */
            // Jika request berupa AJAX, kembalikan JSON error
            if ($request->isAJAX()) {
                return \Config\Services::response()
                    ->setStatusCode(403)
                    ->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki hak akses untuk tindakan ini.']);
            }
            
            // PERBAIKAN: JANGAN GUNAKAN REDIRECT UNTUK MENCEGAH LOOPING!
            // Kita hentikan prosesnya secara paksa dan munculkan pesan Error bawaan CodeIgniter
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('AKSES DITOLAK: Anda tidak memiliki izin untuk membuka halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request
    }
}