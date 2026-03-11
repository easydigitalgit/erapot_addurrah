<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class OrangTuaBaseController extends BaseController
{
    protected $data = [];
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        // Proteksi: Pastikan yang masuk adalah Role Orang Tua (ID 5)
        // Gunakan session() dengan berhati-hati sebelum init siap sepenuhnya
        $session = \Config\Services::session();
        if ($session->get('role_id') != 5) {
            header("Location: " . base_url('login'));
            exit;
        }

        // Kongsikan menu sidebar ke semua view Orang Tua
        $this->data['sidebar_menu'] = $this->getSidebarMenu();
    }

    protected function getColor()
    {
        // Gunakan fungsi global yang sama dengan Admin (jika wujud)
        $sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

        // Jika fungsi tidak wujud, kita cuba tarik terus dari database
        if (empty($sekolahData)) {
            $db = \Config\Database::connect();
            if ($db->tableExists('profil_sekolah')) {
                $sekolahData = $db->table('profil_sekolah')->where('id', 1)->get()->getRowArray();
            }
        }

        // Tetapkan warna dinamik, jika kosong gunakan warna lalai (default) hijau sekolah
        $warna_primary   = $sekolahData['warna_primary'] ?? '#1F7A4D'; 
        $warna_secondary = $sekolahData['warna_secondary'] ?? '#E6F4EC'; 

        return [
            'warna_primary'   => $warna_primary,
            'warna_secondary' => $warna_secondary
        ];
    }

    protected function getSidebarMenu()
    {
        // Sidebar khusus Orang Tua (Perhatikan format mesti konsisten dengan main.php)
        // Saya menyusunnya ke dalam array 'dashboard', 'akademik' dsb agar formatnya
        // sama seperti susunan $navigations di AdminBaseController.
        return [
            'dashboard' => [
                'label'   => 'Dashboard',
                'url'     => '/orangtua/dashboard',
                'icon'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                'submenu' => [] // Wajib ada array kosong walau tiada submenu
            ],
            'akademik' => [
                'label'   => 'Akademik',
                'url'     => '/orangtua/akademik',
                'icon'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                'submenu' => []
            ],
            'tahfidz' => [
                'label'   => 'Tahfidz',
                'url'     => '/orangtua/tahfidz',
                'icon'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>',
                'submenu' => []
            ],
            'kehadiran' => [
                'label'   => 'Kehadiran',
                'url'     => '/orangtua/kehadiran',
                'icon'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                'submenu' => []
            ]
        ];
    }
}   