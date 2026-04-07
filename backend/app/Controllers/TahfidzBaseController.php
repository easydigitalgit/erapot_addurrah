<?php

namespace App\Controllers;

use App\Controllers\BaseController; 
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class TahfidzBaseController extends BaseController
{
    // KITA HAPUS BARIS protected $helpers DI SINI AGAR TIDAK MENIMPA BaseController!

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // --- MANAJEMEN BAHASA KETAT (WAJIB DITAMBAHKAN) ---
        $session = \Config\Services::session();
        if ($session->has('bahasa')) {
            $locale = $session->get('bahasa');
            $this->request->setLocale($locale);
            \Config\Services::language()->setLocale($locale);
            config('App')->defaultLocale = $locale;
        }
    }

    /**
     * Mengambil warna tema dari database<?php
    public function index(): string
    {
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');
        
        // 1. Ambil Total Siswa (Menghitung semua siswa yang ada di tabel siswa)
        $total_siswa = $db->table('siswa')->countAllResults();

        // 2. Ambil Setoran Hari Ini dari tabel setoran_tahfidz
        // Jika tabel setoran_tahfidz belum ada di SQL Mas, abaikan errornya, nanti tinggal dibuat tabelnya.
        $setoran_hari_ini = $db->table('setoran_tahfidz')
                               ->where('tanggal', $hari_ini)
                               ->countAllResults();

        // 3. Kalkulasi Persentase Keaktifan Hari Ini
        $persentase = ($total_siswa > 0) ? round(($setoran_hari_ini / $total_siswa) * 100) : 0;

        // 4. Data Live Feed: 6 Setoran Terakhir (Join dengan tabel siswa dan rombel)
        $setoran_terakhir = $db->table('setoran_tahfidz')
                               ->select('setoran_tahfidz.*, siswa.nama_lengkap, rombel.nama_rombel')
                               ->join('siswa', 'siswa.id = setoran_tahfidz.siswa_id')
                               ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                               ->orderBy('setoran_tahfidz.created_at', 'DESC')
                               ->limit(6)
                               ->get()
                               ->getResultArray();

        // 5. Data Actionable: Santri Perlu Perhatian (Nilai Kurang Lancar / Belum Hafal)
        $perlu_perhatian = $db->table('setoran_tahfidz')
                              ->select('setoran_tahfidz.predikat, setoran_tahfidz.created_at, siswa.nama_lengkap, rombel.nama_rombel')
                              ->join('siswa', 'siswa.id = setoran_tahfidz.siswa_id')
                              ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                              ->whereIn('setoran_tahfidz.predikat', ['Kurang Lancar', 'Belum Hafal'])
                              ->orderBy('setoran_tahfidz.created_at', 'DESC')
                              ->limit(4) // Ambil 4 kasus terbaru
                              ->get()
                              ->getResultArray();

        // 6. Dummy Data Target (Nanti bisa diganti dengan hitungan dari tabel target_tahfidz)
        $target_tercapai = round($total_siswa * 0.35); // Contoh: 35% siswa sudah capai target

        $data = [
            'title'            => 'Dashboard Tahfidz - Rapor Digital',
            'user'             => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'navigations'      => $this->getSidebarMenu(),
            'color'            => $this->getColor(), // Fungsi bawaan BaseController Mas
            'total_siswa'      => $total_siswa,
            'setoran_hari_ini' => $setoran_hari_ini,
            'persentase'       => $persentase,
            'target_tercapai'  => $target_tercapai,
            'setoran_terakhir' => $setoran_terakhir,
            'perhatian'        => $perlu_perhatian // Kirim data peringatan ke view
        ];

        return view('tahfidz/dashboard/index', $data); // Pastikan path view-nya sesuai dengan folder Mas
    }
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
            // Abaikan
        }

        return [
            'warna_primary' => '#10B981', 
            'warna_secondary' => '#D1FAE5' 
        ];
    }

    // ... (Fungsi getSidebarMenu() di bawahnya biarkan sama persis seperti sebelumnya) ...
    /**
     * Menu Sidebar khusus Guru Tahfidz
     */
    /**
     * Menu Sidebar khusus Guru Tahfidz
     */
    protected function getSidebarMenu()
    {
        return [
            [
                'label' => lang('Sidebar.dashboard'),
                'icon'  => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                'url'   => 'tahfidz/dashboard',
                'active'=> url_is('tahfidz/dashboard')
            ],
            [
                'label' => lang('Sidebar.setoran_hafalan'),
                'icon'  => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                'url'   => 'tahfidz/setoran',
                'active'=> url_is('tahfidz/setoran*')
            ],
            [
                'label' => lang('Sidebar.monitoring_target'),
                'icon'  => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                'url'   => 'tahfidz/monitoring',
                'active'=> url_is('tahfidz/monitoring*')
            ],
            // TAMBAHAN MENU NILAI TEORI
            [
                'label' => 'Nilai Teori',
                'icon'  => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'url'   => 'tahfidz/nilai-teori',
                'active'=> url_is('tahfidz/nilai-teori*')
            ],
            [
                'label' => lang('Sidebar.nilai_rapor_tahfidz'),
                'icon'  => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'url'   => 'tahfidz/nilai-rapor',
                'active'=> url_is('tahfidz/nilai-rapor*')
            ],
            'akun_saya' => [
                'url'      => 'tahfidz/akun-saya', 
                'label'    => lang('Sidebar.akun_saya'),
                'icon'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',                
            ],
        ];
    }
}