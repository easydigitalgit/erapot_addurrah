<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class WaliKelasBaseController extends BaseController
{
    protected $data = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // --- MANAJEMEN BAHASA KETAT (Copy dari Admin) ---
        $session = \Config\Services::session();
        if ($session->has('bahasa')) {
            $locale = $session->get('bahasa');
            $this->request->setLocale($locale);
            \Config\Services::language()->setLocale($locale);
            config('App')->defaultLocale = $locale;
        }

        // --- IDENTITAS ROLE UNTUK NAVBAR & FOOTER ---
        $this->data['role_lang'] = 'Wali Kelas';

        $this->data['sidebar_menu'] = $this->getSidebarMenu(); // / <==== tadi di sini double buka kurung () <----- <<---
    }

    protected function getSidebarMenu()
    {
        // 1. Submenu Dashboard
        $sub_dashboard = [
            ['url' => 'wali/ringkasan-kelas', 'label' => lang('Sidebar.class_summary')],
            ['url' => 'wali/perlu-pembinaan', 'label' => lang('Sidebar.needs_guidance')],
        ];
    
        // 2. Submenu Kelas Perwalian
        $sub_kelas_perwalian = [
            ['url' => 'wali/daftar-siswa', 'label' => lang('Sidebar.homeroom_students')],
        ];
    
        // 3. Submenu Monitoring Nilai
        $sub_monitoring_nilai = [
            ['url' => 'wali/progres-nilai', 'label' => lang('Sidebar.subject_progress')],
            ['url' => 'wali/validasi-catatan-guru', 'label' => lang('Sidebar.validate_notes')],
        ];
    
        // 4. Submenu Karakter & Pembinaan
        $sub_karakter = [
            ['url' => 'wali/absensi-kelas', 'label' => lang('Sidebar.class_attendance')],
            ['url' => 'wali/pelanggaran-prestasi', 'label' => lang('Sidebar.violation_achiev')],
            ['url' => 'wali/catatan-walikelas', 'label' => lang('Sidebar.homeroom_notes')],
            ['url' => 'wali/progres-tahfidz', 'label' => lang('Sidebar.tahfidz_progress')],
        ];
    
        // 5. Submenu Rapor
        $sub_rapor = [
            ['url' => 'wali/preview-rapor', 'label' => lang('Sidebar.report_preview')],
        ];
    
        // MAIN NAVIGATION ARRAY
        return [
            'dashboard' => [
                'url'      => '',
                'label'    => lang('Sidebar.dashboard'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>',
                'submenu'  => $sub_dashboard
            ],
            'kelas_perwalian' => [
                'url'      => '',
                'label'    => lang('Sidebar.homeroom_class'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'submenu'  => $sub_kelas_perwalian
            ],
            'monitoring_nilai' => [
                'url'      => '',
                'label'    => lang('Sidebar.grade_monitoring'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>',
                'submenu'  => $sub_monitoring_nilai
            ],
            'karakter_pembinaan' => [
                'url'      => '',
                'label'    => lang('Sidebar.character_guidance'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'submenu'  => $sub_karakter
            ],
            'rapor' => [
                'url'      => '',
                'label'    => lang('Sidebar.report_card'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'submenu'  => $sub_rapor
            ],
            'akun_saya' => [
                'url'      => '/wali/akun-saya', 
                'label'    => lang('Sidebar.akun_saya'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                'submenu'  => []
            ],
        ];
    }
}