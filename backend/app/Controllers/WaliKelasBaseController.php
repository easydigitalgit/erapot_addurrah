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
        // --- IDENTITAS ROLE UNTUK NAVBAR & FOOTER ---
        $this->data['role_lang'] = 'Wali Kelas';

        // ---> INI BARIS YANG SEBELUMNYA HILANG (PENGIRIM MENU KE SIDEBAR) <---
        $this->data['sidebar_menu'] = $this->getSidebarMenu();
    }

    /**
     * Mengambil warna tema dari database
     */
    protected function getColor()
    {
        try {
            $db = \Config\Database::connect();
            $colorData = $db->table('sekolah') 
                            ->select('warna_primary, warna_secondary')
                            ->get()
                            ->getRowArray();

            if ($colorData) {
                return $colorData;
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel belum ada
        }

        // Warna fallback untuk Wali Kelas (misal: Biru)
        return [
            'warna_primary'   => '#10b981', 
            'warna_secondary' => '#ecfdf5' 
        ];
    }

    protected function getSidebarMenu()
    {
        // 1. Submenu Dashboard
        $sub_dashboard = [
            ['url' => 'wali/ringkasan-kelas', 'label' => lang('Sidebar.ringkasan_kelas')],
            ['url' => 'wali/perlu-pembinaan', 'label' => lang('Sidebar.siswa_perlu_pembinaan')],
        ];
    
        // 2. Submenu Kelas Perwalian
        $sub_kelas_perwalian = [
            ['url' => 'wali/daftar-siswa', 'label' => lang('Sidebar.daftar_siswa')],
        ];
    
        // 3. Submenu Monitoring Nilai
        $sub_monitoring_nilai = [
            ['url' => 'wali/progres-nilai', 'label' => lang('Sidebar.progres_nilai_mapel')],
            ['url' => 'wali/validasi-catatan-guru', 'label' => lang('Sidebar.validasi_catatan_guru')],
        ];
    
        // 4. Submenu Karakter & Pembinaan
        $sub_karakter = [
            ['url' => 'wali/absensi-kelas', 'label' => lang('Sidebar.absensi_kelas')],
            ['url' => 'wali/pelanggaran-prestasi', 'label' => lang('Sidebar.pelanggaran_prestasi')],
            ['url' => 'wali/catatan-walikelas', 'label' => lang('Sidebar.catatan_wali_kelas')],
            ['url' => 'wali/progres-tahfidz', 'label' => lang('Sidebar.progres_tahfidz')],
            
            // --- MENU BARU: NILAI EKSTRAKURIKULER ---
            ['url' => 'wali/nilai-ekskul', 'label' => lang('Sidebar.nilai_ekskul')],
        ];
    
        // 5. Submenu Rapor
        $sub_rapor = [
            ['url' => 'wali/preview-rapor', 'label' => lang('Sidebar.preview_rapor_kelas')],
            ['url' => 'wali/tahfidz', 'label' => 'Cetak Rapor Nilai Tahfiz', 'active' => url_is('wali/tahfidz*')],
        ];
    
        // MAIN NAVIGATION ARRAY
        return [
            [
                'label'    => lang('Sidebar.dashboard'),
                'icon'     => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>',
                'submenu'  => $sub_dashboard,
                'active'   => url_is('wali/ringkasan-kelas*') || url_is('wali/perlu-pembinaan*')
            ],
            [
                'label'    => lang('Sidebar.kelas_perwalian'),
                'icon'     => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'submenu'  => $sub_kelas_perwalian,
                'active'   => url_is('wali/daftar-siswa*')
            ],
            [
                'label'    => lang('Sidebar.monitoring_nilai'),
                'icon'     => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>',
                'submenu'  => $sub_monitoring_nilai,
                'active'   => url_is('wali/progres-nilai*') || url_is('wali/validasi-catatan-guru*')
            ],
            [
                'label'    => lang('Sidebar.karakter_pembinaan'),
                'icon'     => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'submenu'  => $sub_karakter,
                'active'   => url_is('wali/absensi-kelas*') || url_is('wali/pelanggaran-prestasi*') || url_is('wali/catatan-walikelas*') || url_is('wali/progres-tahfidz*') || url_is('wali/nilai-ekskul*')
            ],
            [
                'label'    => lang('Sidebar.rapor'),
                'icon'     => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'submenu'  => $sub_rapor,
                'active'   => url_is('wali/preview-rapor*')
            ],
            'akun_saya' => [
                'url'      => 'wali/akun-saya',
                'label'    => lang('Sidebar.akun_saya'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                'active'   => url_is('wali/akun-saya*')
            ],
        ];
    }
}