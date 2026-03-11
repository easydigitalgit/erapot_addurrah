<?php

namespace App\Controllers;

use App\Models\NotifikasiModel;
use App\Controllers\BaseController;
use ReflectionMethod; // Import fungsi sakti PHP

class NotifikasiController extends BaseController
{
   public function index()
    {
        $notifModel = new \App\Models\NotifikasiModel();
        $userId  = session()->get('id');
        $roleKey = session()->get('role_key');

        $data = [
            'title'      => 'Semua Notifikasi',
            'notifikasi' => $notifModel->where('user_id', $userId)
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll(),
            // WARNA: Langsung ambil dari fungsi mandiri agar 100% dinamis dan stabil untuk semua role!
            'color'      => $this->getColor() 
        ];

        // ==============================================================================
        // MENGAMBIL MENU DARI BASE CONTROLLER MASING-MASING
        // ==============================================================================
        $controllerClass = null;

        // 1. Tentukan class tujuan berdasarkan Role
        switch ($roleKey) {
            case 'admin':
                $controllerClass = '\App\Controllers\AdminBaseController';
                break;
            case 'guru':
                $controllerClass = '\App\Controllers\GuruMapelBaseController';
                break;
            case 'wali_kelas':
                $controllerClass = '\App\Controllers\WaliKelasBaseController';
                break;
            case 'orang_tua':
                $controllerClass = '\App\Controllers\OrangTuaBaseController';
                break;
            case 'siswa':
                $controllerClass = '\App\Controllers\SiswaBaseController';
                break;
           case 'guru_tahfidz':
                // Karena BaseController-nya abstract, kita panggil class anaknya saja (DashboardController)
                // Anak ini mewarisi semua fungsi menu dari TahfidzBaseController
                $controllerClass = '\App\Controllers\Tahfidz\DashboardController';
                break;
        }

        // 2. Trik Reflection untuk mengambil Sidebar Menu tanpa error redirect
        if ($controllerClass && class_exists($controllerClass)) {
            $baseInstance = new $controllerClass();

            if (method_exists($baseInstance, 'getSidebarMenu')) {
                $methodMenu = new \ReflectionMethod($controllerClass, 'getSidebarMenu');
                $methodMenu->setAccessible(true);
                $data['sidebar_menu'] = $methodMenu->invoke($baseInstance);
            } else {
                $data['sidebar_menu'] = []; // Jaga-jaga jika fungsi tidak ditemukan
            }
        } else {
            $data['sidebar_menu'] = [];
        }

        return view('notifikasi/index', $data);
    }

    public function markAllRead()
    {
        $notifModel = new NotifikasiModel();
        $userId = session()->get('id');

        $notifModel->where('user_id', $userId)
                   ->where('is_read', 0)
                   ->set(['is_read' => 1])
                   ->update();

        return $this->response->setJSON(['status' => 'success']);
    }

    public function markAsRead($id)
    {
        $notifModel = new NotifikasiModel();
        $userId = session()->get('id');

        $notif = $notifModel->where('id', $id)->where('user_id', $userId)->first();
        if ($notif && $notif['is_read'] == 0) {
            $notifModel->update($id, ['is_read' => 1]);
        }
        return $this->response->setJSON(['status' => 'success']);
    }

    // Fungsi Mandiri untuk mengambil warna dari Database agar selalu stabil
    protected function getColor()
    {
        $sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

        if (empty($sekolahData)) {
            $db = \Config\Database::connect();
            if ($db->tableExists('profil_sekolah')) {
                $sekolahData = $db->table('profil_sekolah')->where('id', 1)->get()->getRowArray();
            }
        }

        return [
            'warna_primary'   => $sekolahData['warna_primary'] ?? '#1F7A4D',
            'warna_secondary' => $sekolahData['warna_secondary'] ?? '#E6F4EC'
        ];
    }
}