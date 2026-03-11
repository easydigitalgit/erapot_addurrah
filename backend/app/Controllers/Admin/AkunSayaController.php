<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\UserModel;

class AkunSayaController extends AdminBaseController
{
    public function index()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $userData = $userModel->find($userId);

        if (!$userData) return redirect()->to('/logout');

        // AMBIL DATA LOG LOGIN TERBARU (Maks 5)
        $db = \Config\Database::connect();
        $loginLogs = $db->table('login_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // AMBIL PREFERENSI USER
        $prefs = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if (!$prefs) {
            // Default jika belum pernah setting
            $prefs = ['notif_login' => 0, 'two_factor' => 0, 'bahasa' => 'id', 'theme' => 'light', 'notif_email' => 0, 'notif_sistem' => 0, 'notif_update' => 0];
        }

        // ==========================================
        // TAMBAHAN BARU: AMBIL SEMUA ROLE USER
        // ==========================================
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];
        
        // Gabungkan role dasar dari session jika belum masuk list (mencegah duplikat)
        $roleIds = array_column($dbRoles, 'role_id');
        if (!empty($userData['role_id']) && !in_array($userData['role_id'], $roleIds)) {
            $dbRoles[] = [
                'role_id' => $userData['role_id'], 
                'role_name' => session()->get('role_label') // fallback nama role
            ];
        }

        // Bungkus semua data untuk dikirim ke View
        $data = [
            'user'        => $userData,
            'login_logs'  => $loginLogs,
            'prefs'       => $prefs,
            'semua_role'  => $dbRoles, // <-- INI KUNCI AGAR JEJERAN BADGE MUNCUL
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor()
        ];

        return view('admin/akun-saya', $data);
    }

    public function updateProfile()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();

        $dataToUpdate = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'email'           => $this->request->getPost('email'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'no_darurat'      => $this->request->getPost('no_darurat'),
            'alamat_domisili' => $this->request->getPost('alamat')
        ];

        try {
            $userModel->update($userId, $dataToUpdate);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Profil berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // FUNGSI GANTI PASSWORD
    // ==========================================
    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = session()->get('id') ?? session()->get('user_id');
        $oldPass = $this->request->getPost('old_password');
        $newPass = $this->request->getPost('new_password');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Verifikasi password lama (Bisa hash bcrypt atau text biasa)
        if (!password_verify($oldPass, $user['password']) && $oldPass !== $user['password']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password lama Anda salah!']);
        }

        // Simpan password baru
        $userModel->update($userId, ['password' => password_hash($newPass, PASSWORD_DEFAULT)]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    }

    // ==========================================
    // FUNGSI SIMPAN PREFERENSI & KEAMANAN
    // ==========================================
    public function updatePreferences()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = session()->get('id') ?? session()->get('user_id');
        $db = \Config\Database::connect();

        // Simpan preferensi bahasa ke Session agar mudah dibaca CI4
        $bahasa = $this->request->getPost('bahasa');
        session()->set('bahasa', $bahasa);
        $theme  = $this->request->getPost('theme') ?: 'light';

        $data = [
            'user_id'      => $userId,
            'notif_login'  => $this->request->getPost('notif_login') ?: 0,
            'two_factor'   => $this->request->getPost('two_factor') ?: 0,
            'bahasa'       => $bahasa,
            'theme'        => $theme,
            'notif_email'  => $this->request->getPost('notif_email') ?: 0,
            'notif_sistem' => $this->request->getPost('notif_sistem') ?: 0,
            'notif_update' => $this->request->getPost('notif_update') ?: 0,
        ];

        $existing = $db->table('user_preferences')->where('user_id', $userId)->get()->getRow();
        if ($existing) {
            $db->table('user_preferences')->where('user_id', $userId)->update($data);
        } else {
            $db->table('user_preferences')->insert($data);
        }

        // KUNCI PERBAIKAN: Simpan ke Session CodeIgniter agar efeknya instan saat di-refresh!
        session()->set('theme', $theme);
        session()->set('locale', $bahasa);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Preferensi disimpan.']);
    }

    public function uploadAvatar()
    {
        // ... [Sama persis seperti kode asli lu, tidak gw ubah] ...
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $userData = $userModel->find($userId);
        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {
            $path = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($path)) mkdir($path, 0777, true);
            if (!empty($userData['foto_profil']) && file_exists($path . $userData['foto_profil'])) unlink($path . $userData['foto_profil']);

            $newName = $fileAvatar->getRandomName();
            $fileAvatar->move($path, $newName);

            try {
                \Config\Services::image()->withFile($path . $newName)->fit(250, 250, 'center')->save($path . $newName, 70);
            } catch (\Exception $e) {
            }

            $userModel->update($userId, ['foto_profil' => $newName]);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Foto profil berhasil disimpan!',
                'new_avatar_url' => base_url('assets/uploads/avatars/' . $newName)
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca file gambar.']);
    }
}
