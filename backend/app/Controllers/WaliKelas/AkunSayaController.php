<?php

namespace App\Controllers\WaliKelas;

use App\Models\Admin\UserModel;
use App\Controllers\WaliKelasBaseController;

class AkunSayaController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();

        $user = $db->table('users u')
            ->select('u.foto_profil, u.username, u.email, u.created_at, g.*')
            ->join('guru_tendik g', 'g.user_id = u.id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();

        $semua_role = [];
        try {
            $semua_role = $db->table('user_roles')
                ->select('roles.role_name')
                ->join('roles', 'roles.id = user_roles.role_id')
                ->where('user_roles.user_id', $userId)
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
        }

        // ==========================================
        // AMBIL LOG LOGIN (SUPER CEPAT TANPA TABLE-EXISTS)
        // ==========================================
        $login_logs = [];
        try {
            $login_logs = $db->table('login_logs')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            try {
                $login_logs = $db->table('auth_logins')
                    ->where('email', $user['email'] ?? session()->get('email'))
                    ->orderBy('created_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
            } catch (\Exception $e2) {
                // Abaikan
            }
        }

        $prefs = [
            'theme'  => session()->get('theme') ?? 'light',
            'bahasa' => session()->get('bahasa') ?? 'id'
        ];

        $data = [
            'title'       => lang('WaliKelas/AkunSaya.page_title') ?? 'Akun Saya - Wali Kelas',
            'user'        => $user,
            'semua_role'  => $semua_role,
            'login_logs'  => $login_logs,
            'prefs'       => $prefs,
            'color'       => $this->getColor(),
            'navigations' => $this->getSidebarMenu()
        ];

        return view('WaliKelas/akun-saya', $data);
    }

    public function updateProfile()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');

        $email = $this->request->getPost('email');
        $nama  = $this->request->getPost('nama_lengkap');
        $no_hp = $this->request->getPost('no_hp');

        $db->table('users')->where('id', $userId)->update([
            'email'      => $email,
            'full_name'  => $nama,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->table('guru_tendik')->where('user_id', $userId)->update([
            'nama_lengkap' => $nama,
            'no_hp'        => $no_hp,
            'alamat'       => $this->request->getPost('alamat')
        ]);

        session()->set('nama_lengkap', $nama);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('WaliKelas/AkunSaya.js_success') ?? 'Profil berhasil diperbarui.',
            'token'   => csrf_hash()
        ]);
    }

    public function uploadAvatar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = session()->get('id') ?? session()->get('user_id');
        $db = \Config\Database::connect();
        $userModel = new \App\Models\Admin\UserModel();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {
            $path = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($path)) mkdir($path, 0777, true);

            $currentUser = isset($userData) ? $userData : (isset($user) ? $user : []);

            if (!empty($currentUser['foto_profil']) && file_exists($path . $currentUser['foto_profil'])) {
                @unlink($path . $currentUser['foto_profil']);
            }

            $originalName = $fileAvatar->getRandomName();
            $fileAvatar->move($path, $originalName);

            $webpName = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';
            $finalName = $originalName;

            try {
                \Config\Services::image()
                    ->withFile($path . $originalName)
                    ->fit(250, 250, 'center')
                    ->convert(IMAGETYPE_WEBP)
                    ->save($path . $webpName, 75);

                if (file_exists($path . $webpName)) {
                    @unlink($path . $originalName);
                    $finalName = $webpName;
                }
            } catch (\Exception $e) {
            }

            $userModel->update($userId, ['foto_profil' => $finalName]);
            session()->set('foto_profil', $finalName);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => lang('WaliKelas/AkunSaya.js_photo_updated') ?? 'Foto profil berhasil diperbarui.',
                'new_avatar_url' => base_url('assets/uploads/avatars/' . $finalName),
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah foto.', 'token' => csrf_hash()]);
    }

    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $oldPass = $this->request->getPost('old_password');
        $newPass = $this->request->getPost('new_password');
        $userId  = session()->get('id') ?? session()->get('user_id');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        $dbPasswordField = isset($user['password']) ? $user['password'] : $user['password_hash'];

        if (!password_verify($oldPass, $dbPasswordField) && $oldPass !== $dbPasswordField) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => lang('WaliKelas/AkunSaya.js_err_pwd_match') ?? 'Password lama salah.',
                'token' => csrf_hash()
            ]);
        }

        $db->table('users')->where('id', $userId)->update([
            'password' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui.',
            'token' => csrf_hash()
        ]);
    }

    public function updatePreferences()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $theme  = $this->request->getPost('theme');
        $bahasa = $this->request->getPost('bahasa');
        $userId = session()->get('id') ?? session()->get('user_id');

        session()->set([
            'theme'  => $theme,
            'bahasa' => $bahasa
        ]);

        try {
            $db = \Config\Database::connect();
            $db->table('users')->where('id', $userId)->update([
                'theme'  => $theme,
                'bahasa' => $bahasa
            ]);
        } catch (\Exception $e) {
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('WaliKelas/AkunSaya.js_success') ?? 'Preferensi berhasil disimpan.',
            'token'   => csrf_hash()
        ]);
    }

    protected function getColor()
    {
        try {
            $db = \Config\Database::connect();
            $colorData = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
            if ($colorData) return $colorData;
        } catch (\Exception $e) {
        }

        return [
            'warna_primary' => '#3b82f6',
            'warna_secondary' => '#eff6ff'
        ];
    }
}
