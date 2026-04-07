<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class GuruMapelBaseController extends BaseController
{
    protected $data = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->data['role_lang'] = 'Guru Mapel';

        $this->data['sidebar_menu'] = $this->getSidebarMenu();
    }

    protected function getSidebarMenu()
    {
        // 1. Submenu Kelas Mengajar
        $sub_kelas_mengajar = [
            ['url' => '/guru/daftar-kelas-mapel', 'label' => lang('Sidebar.daftar_kelas')],
            ['url' => '/guru/daftar-siswa', 'label' => lang('Sidebar.daftar_siswa')],
        ];

        // 2. Submenu Penilaian Akademik
        $sub_penilaian_akademik = [
            ['url' => '/guru/nilai-kolektif', 'label' => lang('Sidebar.nilai_kolektif')],
            ['url' => '/guru/nilai-formatif', 'label' => lang('Sidebar.nilai_formatif')],
            ['url' => '/guru/nilai-sumatif', 'label' => lang('Sidebar.nilai_sumatif')],
            ['url' => '/guru/nilai-rapor', 'label' => lang('Sidebar.nilai_rapor')],
        ];

        // 3. Submenu Sikap & Karakter
        $sub_sikap_karakter = [
            ['url' => '/guru/observasi-sikap', 'label' => lang('Sidebar.observasi_sikap')],
            ['url' => '/guru/akhlak-siswa', 'label' => lang('Sidebar.akhlak_siswa')],
        ];

        // 4. Submenu Materi & Soal
        $sub_materi_soal = [
            ['url' => '/guru/upload-materi', 'label' => lang('Sidebar.upload_materi')],
            ['url' => '/guru/bank-soal', 'label' => lang('Sidebar.bank_soal')],
        ];

        // MAIN NAVIGATION ARRAY
        return [
            'dashboard' => [
                'url'      => '/guru/dashboard',
                'label'    => lang('Sidebar.dashboard'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>',
                'submenu'  => []
            ],
            'kelas_mengajar' => [
                'url'      => '',
                'label'    => lang('Sidebar.kelas_mengajar'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'submenu'  => $sub_kelas_mengajar
            ],
            'penilaian_akademik' => [
                'url'      => '',
                'label'    => lang('Sidebar.penilaian_akademik'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'submenu'  => $sub_penilaian_akademik
            ],
            'sikap_karakter' => [
                'url'      => '',
                'label'    => lang('Sidebar.sikap_karakter'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'submenu'  => $sub_sikap_karakter
            ],
            'materi_soal' => [
                'url'      => '',
                'label'    => lang('Sidebar.materi_soal'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>',
                'submenu'  => $sub_materi_soal
            ],
            'akun_saya' => [
                'url'      => '/guru/akun-saya',
                'label'    => lang('Sidebar.akun_saya'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                'submenu'  => []
            ],
        ];
    }

    protected function getColor()
    {
        $uri = service('uri');
        $currentSegment = 'dashboard';
        if ($uri->getTotalSegments() >= 2) {
            $currentSegment = $uri->getSegment(2);
        }

        $nav_items = $navigations ?? $sidebar_menu ?? [];

        $sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

        $app_name       = 'Rapor Digital';
        $app_sub        = $sekolahData['nama_sekolah'] ?? 'Rapor Digital';
        $warna_primary  = $sekolahData['warna_primary'] ?? '#1F7A4D';
        $warna_secondary = $sekolahData['warna_secondary'] ?? '#E6F4EC';

        return [
            'warna_primary' => $warna_primary,
            'warna_secondary' => $warna_secondary
        ];
    }
}
