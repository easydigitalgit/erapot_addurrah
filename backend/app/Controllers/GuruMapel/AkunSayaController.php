<?php
// File: app/Controllers/GuruMapel/AkunSayaController.php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use App\Models\Admin\UserModel;

class AkunSayaController extends GuruMapelBaseController
{
    public function index()
    {        
    
        $userId = session()->get('id') ?? session()->get('user_id');
    // ... kode lainnya ...
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) return redirect()->to('/logout');

        $db = \Config\Database::connect();

        // Ambil Log Login
        $loginLogs = $db->table('login_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Ambil Preferensi
        $prefs = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if (!$prefs) {
            $prefs = ['notif_login' => 0, 'two_factor' => 0, 'bahasa' => 'id', 'theme' => 'light', 'notif_email' => 0, 'notif_sistem' => 0, 'notif_update' => 0];
        }

        // ==========================================================
        // INI BAGIAN YANG DIUBAH: AKTIFKAN MULTI-BAHASA (LOCALE)
        // ==========================================================
        // 1. Ambil kode bahasa dari database (contoh: 'en', 'id', 'ar')
        $bahasaTerpilih = $prefs['bahasa'] ?? 'id'; 
        
        // 2. Perintahkan CodeIgniter untuk pakai bahasa tersebut
        \Config\Services::language()->setLocale($bahasaTerpilih);
        // ==========================================================

        // Ambil Semua Role
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];
        $roleIds = array_column($dbRoles, 'role_id');
        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = [
                'role_id' => $user['role_id'], 
                'role_name' => session()->get('role_label')
            ];
        }

        $data = [
            'title'       => lang('Akun.page_title'), // Title tab browser juga ikut berubah
            'user'        => $user, 
            'login_logs'  => $loginLogs, 
            'prefs'       => $prefs,    
            'semua_role'  => $dbRoles,  
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
        ];
        
        return view('GuruMapel/akun-saya', $data); 
    }

    // ==========================================
    // FUNGSI BARU UNTUK MENERIMA REQUEST AJAX
    // ==========================================

    public function updateProfile()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();

        // Ambil data dari request POST
        $data = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'email'           => $this->request->getPost('email'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'no_darurat'      => $this->request->getPost('no_darurat'),
            'alamat_domisili' => $this->request->getPost('alamat')
        ];

        // Validasi sederhana (pastikan email tidak dipakai user lain)
        $cekEmail = $userModel->where('email', $data['email'])->where('id !=', $userId)->first();
        if ($cekEmail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email sudah digunakan oleh akun lain!']);
        }

        // Update ke database
        if ($userModel->update($userId, $data)) {
            // Update session nama agar di header/navbar langsung berubah
            session()->set('nama_lengkap', $data['nama_lengkap']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Profil berhasil diperbarui!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui profil.']);
    }

    public function updatePassword()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');

        // Verifikasi password lama
        if (!password_verify($oldPassword, $user['password'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password lama salah!']);
        }

        // Hash password baru dan update
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if ($userModel->update($userId, ['password' => $hashedPassword])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Password berhasil diubah.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengubah password.']);
    }

    public function updatePreferences()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $db = \Config\Database::connect();
        
        $data = [
            'user_id'      => $userId,
            'notif_login'  => $this->request->getPost('notif_login'),
            'two_factor'   => $this->request->getPost('two_factor'),
            'bahasa'       => $this->request->getPost('bahasa'),
            'theme'        => $this->request->getPost('theme'),
            'notif_email'  => $this->request->getPost('notif_email'),
            'notif_sistem' => $this->request->getPost('notif_sistem'),
            'notif_update' => $this->request->getPost('notif_update'),
        ];

        // Cek apakah user sudah punya data preferensi
        $cek = $db->table('user_preferences')->where('user_id', $userId)->get()->getRow();

        if ($cek) {
            $db->table('user_preferences')->where('user_id', $userId)->update($data);
        } else {
            $db->table('user_preferences')->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Preferensi disimpan!']);
    }

    public function uploadAvatar()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $file = $this->request->getFile('avatar');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi tipe dan ukuran
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/uploads/avatars', $newName);

            // Hapus foto lama jika ada
            $oldUser = $userModel->find($userId);
            if (!empty($oldUser['foto_profil']) && file_exists(FCPATH . 'assets/uploads/avatars/' . $oldUser['foto_profil'])) {
                unlink(FCPATH . 'assets/uploads/avatars/' . $oldUser['foto_profil']);
            }

            // Simpan nama file ke DB
            $userModel->update($userId, ['foto_profil' => $newName]);

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Foto profil berhasil diunggah!',
                'new_avatar_url' => base_url('assets/uploads/avatars/' . $newName)
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah foto.']);
    }
}