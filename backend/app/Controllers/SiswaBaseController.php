<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SiswaBaseController extends BaseController
{
    protected $data = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        // --- IDENTITAS ROLE UNTUK NAVBAR & FOOTER ---
        $this->data['role_lang'] = 'Siswa'; 

        $this->data['sidebar_menu'] = $this->getSidebarMenu(); // / <==== tadi di sini double buka kurung () <----- <<---
    }

    protected function getSidebarMenu()
    {
        
    
        // MAIN NAVIGATION ARRAY
        return [
        'dashboard' => [
            'url'      => '/siswa/dashboard',
            'label'    => lang('Sidebar.dashboard'),
            'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>',
            'submenu'  => []
        ],
        ];
    }

    protected function getColor()
    {
        $uri = service('uri');
        
        // PERBAIKAN: Gunakan getTotalSegments() sebelum memanggil getSegment(2)
        $currentSegment = 'dashboard';
        if ($uri->getTotalSegments() >= 2) {
            $currentSegment = $uri->getSegment(2);
        }

        $nav_items = $navigations ?? $sidebar_menu ?? []; 

        $sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

        $app_name       = 'Rapor Digital';
        $app_sub        = $sekolahData['nama_sekolah'] ?? 'Rapor Digital';
        $warna_primary  = $sekolahData['warna_primary'] ?? '#1F7A4D'; // Default Emerald Green
        $warna_secondary= $sekolahData['warna_secondary'] ?? '#E6F4EC'; // Default Soft Green

        return [
            'warna_primary' => $warna_primary,
            'warna_secondary' => $warna_secondary
        ];
    }
}