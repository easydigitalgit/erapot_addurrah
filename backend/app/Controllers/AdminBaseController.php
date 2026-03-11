<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected $data = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // --- MANAJEMEN BAHASA KETAT ---
        $session = \Config\Services::session();
        if ($session->has('bahasa')) {
            $locale = $session->get('bahasa');
            $this->request->setLocale($locale);
            \Config\Services::language()->setLocale($locale);
            config('App')->defaultLocale = $locale;
        }

        // --- IDENTITAS ROLE UNTUK NAVBAR & FOOTER ---
        $this->data['role_lang'] = 'Admin'; 

        $this->data['sidebar_menu'] = $this->getSidebarMenu(); 
    }

    protected function getSidebarMenu()
    {
        $sub_dashboard = [
            ['url' => '/admin/dashboard-statistik', 'label' => lang('Sidebar.school_stats')],
            ['url' => '/admin/dashboard-insight', 'label' => lang('Sidebar.academic_insight')],
        ];
    
        $sub_manajemen_pengguna = [
            ['url' => '/admin/users', 'label' => lang('Sidebar.users')],
            ['url' => '/admin/siswa', 'label' => lang('Sidebar.students')],
            ['url' => '/admin/guru-tendik', 'label' => lang('Sidebar.teachers_staff')],
            ['url' => '/admin/orangtua', 'label' => lang('Sidebar.parents')],
        ];
    
        $sub_master_akademik = [
            ['url' => '/admin/tingkat-rombel', 'label' => lang('Sidebar.level_class')],
            ['url' => '/admin/mata-pelajaran', 'label' => lang('Sidebar.subjects')],
            ['url' => '/admin/wali-kelas', 'label' => lang('Sidebar.homeroom_teachers')],
            ['url' => '/admin/mapping-mapel', 'label' => lang('Sidebar.subject_mapping')],
        ];
    
        $sub_konfigurasi = [
            ['url' => '/admin/tahun-ajaran', 'label' => lang('Sidebar.academic_year')],
            ['url' => '/admin/kurikulum', 'label' => lang('Sidebar.curriculum')],
            ['url' => '/admin/jadwal-pelajaran', 'label' => lang('Sidebar.schedule')],
            ['url' => '/admin/target-tahfidz', 'label' => lang('Sidebar.tahfidz_target')],
            ['url' => '/admin/aturan-nilai', 'label' => lang('Sidebar.scoring_rules')],
        ];
    
        $sub_penilaian = [
            ['url' => '/admin/input-nilai-siswa', 'label' => lang('Sidebar.input_grades')],
            ['url' => '/admin/monitoring-input', 'label' => lang('Sidebar.monitor_grades')],
            ['url' => '/admin/validasi-nilai', 'label' => lang('Sidebar.validate_grades')],
        ];
    
        $sub_rapor = [
            ['url' => '/admin/preview-rapor', 'label' => lang('Sidebar.preview_report')],
            ['url' => '/admin/cetak-rapor', 'label' => lang('Sidebar.print_report')], 
            ['url' => '/admin/cetak-leger', 'label' => lang('Sidebar.ledger')],
        ];
    
        $sub_sistem = [
            ['url' => '/admin/profile-sekolah', 'label' => lang('Sidebar.school_profile')],
            ['url' => '/admin/hak-akses', 'label' => lang('Sidebar.access_rights')],
            ['url' => '/admin/backup', 'label' => lang('Sidebar.backup')],
        ];
    
        return [
            'dashboard' => [
                'url'      => '',
                'label'    => lang('Sidebar.dashboard'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>',
                'submenu'  => $sub_dashboard
            ],
            'manajemen_pengguna' => [
                'url'      => '',
                'label'    => lang('Sidebar.user_management'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'submenu'  => $sub_manajemen_pengguna
            ],
            'master_akademik' => [
                'url'      => '',
                'label'    => lang('Sidebar.academic_master'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                'submenu'  => $sub_master_akademik
            ],
            'konfigurasi_akademik' => [
                'url'      => '',
                'label'    => lang('Sidebar.academic_config'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
                'submenu'  => $sub_konfigurasi
            ],
            'penilaian' => [
                'url'      => '',
                'label'    => lang('Sidebar.grading'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>',
                'submenu'  => $sub_penilaian
            ],
            'rapor_laporan' => [
                'url'      => '',
                'label'    => lang('Sidebar.reports'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'submenu'  => $sub_rapor
            ],
            'sistem' => [
                'url'      => '',
                'label'    => lang('Sidebar.system'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>',
                'submenu'  => $sub_sistem
            ],
            'akun_saya' => [
                'url'      => '/admin/akun-saya',
                'label'    => lang('Sidebar.akun_saya'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
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